<?php

	class Model
	{
		static $connexions = array();	// Liste des connexions aux bases de données
		public $conf = 'default';		// Nom de la base de donnée à laquelle se connecter (à configurer dans /config/conf.php)
		public $table = false;			// Nom de la table dans laquelle aller chercher les informations
		public $db;						// Objet PDO de la connexion à la base de données
		public $primaryKey = 'id';		// Nom du champs de la clé primaire de la table
		public $errors = array();		// Tableau des éventuelles erreurs présentes lors de la validation d'un formulaire (VALIDATION A FAIRE)

		/**
		* Constructeur : il va se connecter à la base de données si ça n'est pas déjà fait, et inscrit le nom de la table dans $this->table
		**/
		public function __construct()
		{
			// Vérification que l'on ne se soit pas déjà connecté à la base de données
			$conf = Conf::$databases[$this->conf];
			if(isset(Model::$connexions[$this->conf]))
			{
				$this->db = Model::$connexions[$this->conf];
			}
			else
			{
				// Connexion à la base de données et placement de l'objet PDO de la connexion dans Model::$connexions['nom_base']
				try
				{
					$pdo = new PDO(
						'pgsql:host=' . $conf['host'] . ';dbname=' . $conf['database'] . ';',
						$conf['login'],
						$conf['password']
					);
					if(Conf::$debug >= 1)
						$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
					Model::$connexions[$this->conf] = $pdo;
					$this->db = $pdo;
				}
				catch(PDOException $e)
				{
					if(Conf::$debug >= 1)
						die($e->getMessage());
					else
						die('Connexion impossible à la base de données');
				}
			}

			// Si $this->table ne contient pas le nom de la table demandée, on la met
			if($this->table === false)
			{
				$this->table = lcfirst(get_class($this));
				if($this->table[strlen($this->table) - 1] == 'y')
				{
					$this->table[strlen($this->table) - 1] = 'i';
					$this->table .= 'es';
				}
				elseif($this->table[strlen($this->table) - 1] != 's')
					$this->table .= 's';
			}
		}

		/**
		* Fonction permettant d'exécuter une requête, elle renvoie soit les données (dans le cas d'un SELECT), soit true (si erreur => exception)
		* A FAIRE : GÉRER LES EXCEPTIONS
		**/
		public function query($sql)
		{
			$pre = $this->db->prepare($sql);
			$pre->execute();

			if(strpos('SELECT', $sql) == 0)
			{
				$d = $pre->fetchAll(PDO::FETCH_OBJ);
				$d2 = array();
				foreach ($d as $v) {
					$d2[] = (array) $v;
				}
				return $d2;
			}
			else
			{
				return true;
			}
		}

		/**
		* Fonction permettant de retrouver des données
		* @param $req['fields'] : champs à rechercher (SELECT ...), sinon *
		* @param $req['tables'] : tables dans lesquelles rechercher (FROM ...), sinon $this->table
		* @param $req['conditions'] : conditions de la recherche (WHERE ...), sinon true
		* @param $req['order'] : ordre dans lequel les données seront renvoyés (ORDER ...), sinon rien
		* @param $req['limit'] : limite de la recherche (LIMIT ...), sinon rien
		**/
		public function find($req = array())
		{
			$sql = 'SELECT ';

			if(!empty($req['fields']))
			{
				if(is_array($req['fields']))
				{
					$sql .= implode(', ', $req['fields']) . ' ';
				}
				else
				{
					$sql .= $req['fields'] . ' ';
				}
			}
			else
			{
				$sql .= '* ';
			}

			$sql .= 'FROM ';

			if(!empty($req['tables']))
			{
				if(is_array($req['tables']))
				{
					$sql .= implode(', ', $req['tables']) . ' ';
				}
				else
				{
					$sql .= $req['tables'];
				}
			}
			else
			{
				$sql .= $this->table;
			}

			if(!empty($req['conditions']))
			{
				$sql .= ' WHERE ';

				if(is_array($req['conditions']))
				{
					$conditions = array();
					foreach ($req['conditions'] as $k => $v)
					{
						if($v === true) $v = 'true';
						if($v === false) $v = 'false';
						
						$conditions[] = $k . ' = \'' . str_replace('\'', '\'\'', $v) . '\'';
					}
					$sql .= implode(' AND ', $conditions);
				}
				else
				{
					$sql .= $req['conditions'];
				}
			}

			if(!empty($req['order']))
				$sql .= ' ORDER BY ' . $req['order'];

			if(!empty($req['limit']))
				$sql .= ' LIMIT ' . $req['limit'];

			return $this->query($sql);
		}

		/**
		* Fonction débile juste pour ne pas avoir un tableau à une valeurs lors de certaines recherches
		**/
		public function findFirst($req = array())
		{
			return current($this->find($req));
		}

		/**
		* Fonction permettant d'enregistrer des données (ajout ou modification).
		* Si la clé primaire est renseignée on fait une modification, sinon une insertion
		* @param $data['champ'] = 'valeur'
		**/
		public function save($data = array())
		{
			if(!empty($data) AND is_array($data))
			{
				if(empty($data[$this->primaryKey]))
				{
					$sql = 'INSERT INTO ' . $this->table . ' ';

					$keys = $values = array();
					foreach($data as $k => $v) {
						$keys[] = $k;
						$values[] = '\'' . str_replace('\'', '\'\'', $v) . '\'';
					}
					$keys = implode(', ', $keys);
					$values = implode(', ', $values);

					$sql .= '(' . $keys . ') VALUES (' . $values . ')';
				}
				else
				{
					$sql = 'UPDATE ' . $this->table . ' SET ';

					$fields = array();
					foreach($data as $k => $v){
						if($k != $this->primaryKey)
							$fields[] = $k . ' = \'' . str_replace('\'', '\'\'', $v) . '\'';
					}
					$sql .= implode(', ', $fields) . ' WHERE ' . $this->primaryKey . ' = \'' . str_replace('\'', '\'\'', $data[$this->primaryKey]) . '\'';
				}
				
				return $this->query($sql);
			}
			else
			{
				return false;
			}
		}

		/**
		* Fonction supplémentaire permettant la mise à jour de données (si condition différente de id = valeur)
		* @param $req['fields'] : champs à modifier
		* @param $req['fields']['champ'] = 'valeur' ou $req['fields'] = 'champ = valeur'
		* @param $req['conditions'] : condition de la mise à jour
		* @param $req['conditions']['champ'] = 'valeur' ou $req['conditions'] = 'champ = valeur'
		**/
		public function update($req = array())
		{
			$sql = 'UPDATE ' . $this->table . ' ';

			if(!empty($req['fields']))
			{
				$sql .= 'SET ';

				if(is_array($req['fields']))
				{
					$fields = array();
					foreach($req['fields'] as $k => $v){
						if($k != $this->primaryKey)
							$fields[] = $k . ' = \'' . str_replace('\'', '\'\'', $v) . '\'';
					}
					$sql .= implode(', ', $fields);
				}
				else
				{
					$sql .= $req['fields'];
				}
			}
			else
			{
				return false;
			}

			if(!empty($req['conditions']))
			{
				$sql .= ' WHERE ';

				if(is_array($req['conditions']))
				{
					$conditions = array();
					foreach($req['conditions'] as $k => $v)
					{
						if($v === true) $v = 'true';
						if($v === false) $v = 'false';

						$conditions[] = $k . ' = \'' . str_replace('\'', '\'\'', $v) . '\'';
					}
					$sql .= implode(' AND ', $conditions);
				}
				else
				{
					$sql .= $req['conditions'];
				}
			}

			return $this->query($sql);
		}

		/**
		* Fonction permettant la suppression de données
		* @param $conds : conditions de la suppression
		* @param $conds['champ'] = 'valeur' ou $conds = 'champ = valeur'
		**/
		public function delete($conds = array())
		{
			if(!empty($conds))
			{
				if(is_array($conds))
				{
					$conditions = array();
					foreach($conds as $k => $v)
					{
						if($v === true) $v = 'true';
						if($v === false) $v = 'false';

						$conditions[] = $k . ' = \'' . str_replace('\'', '\'\'', $v) . '\'';
					}
				}
				else
				{
					$conditions = array($conds);
				}

				$sql = 'DELETE FROM ' . $this->table . ' WHERE ' . implode(' AND ', $conditions);
				
				$this->query($sql);
			}
			else
			{
				return false;
			}
		}
	}
