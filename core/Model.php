<?php

	class Model
	{
		static $connexions = array();	// Liste des connexions aux bases de données
		public $conf = 'default';		// Nom de la base de donnée à laquelle se connecter (à configurer dans /config/conf.php)
		public $table = false;			// Nom de la table dans laquelle aller chercher les informations
		public $db;						// Objet PDO de la connexion à la base de données
		public $primaryKey = 'id';

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
				return $pre->fetchAll(PDO::FETCH_OBJ);
			else
				return true;
		}

		/**
		* Fonction permettant de retrouver des données
		* @var $req['fields'] : champs à rechercher (SELECT ...), sinon *
		* @var $req['tables'] : tables dans lesquelles rechercher (FROM ...), sinon $this->table
		* @var $req['conditions'] : conditions de la recherche (WHERE ...), sinon true
		* @var $req['order'] : ordre dans lequel les données seront renvoyés (ORDER ...), sinon rien
		* @var $req['limit'] : limite de la recherche (LIMIT ...), sinon rien
		* A FAIRE : GÉRER LES TABLEAUX DANS $REQ['ELEM']
		**/
		public function find($req = array())
		{
			$sql = 'SELECT ';

			if(!empty($req['fields']))
				$sql .= $req['fields'] . ' ';
			else
				$sql .= '* ';

			$sql .= 'FROM ';

			if(!empty($req['tables']))
				$sql .= $req['tables'] . ' ';
			else
				$sql .= $this->table . ' ';

			$sql .= 'WHERE ';

			if(!empty($req['conditions']))
				$sql .= $req['conditions'];
			else
				$sql .= 'true';

			if(!empty($req['order']))
				$sql .= ' ORDER BY ' . $req['order'];

			if(!empty($req['limit']))
				$sql .= ' LIMIT ' . $req['limit'];

			return $this->query($sql);
		}

		/**
		* Fonction permettant d'enregistrer des données (ajout ou modification).
		* Si la clé primaire est renseignée on fait une modification, sinon une insertion
		* @var $data['champ'] = 'valeur'
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
		* @var $req['fields'] : champs à modifier
		* @var $req['fields']['champ'] = 'valeur' ou $req['fields'] = 'champ = valeur'
		* @var $req['conditions'] : condition de la mise à jour
		* @var $req['conditions']['champ'] = 'valeur' ou $req['conditions'] = 'champ = valeur'
		**/
		public function update($req = array())
		{
			$sql = 'UPDATE ' . $this->table . ' ';

			if(!empty($req['fields']))
			{
				if(is_array($req['fields']))
				{
					$fields = array();
					foreach($req['fields'] as $k => $v){
						if($k != $this->primaryKey)
							$fields[] = $k . ' = \'' . str_replace('\'', '\'\'', $v) . '\'';
					}
				}
				else
				{
					$fields = array($req['fields']);
				}
			}
			else
			{
				return false;
			}

			$sql .= 'SET ' . implode(', ', $fields);

			if(!empty($req['conditions']))
			{
				if(is_array($req['conditions']))
				{
					$conditions = array();
					foreach($req['conditions'] as $k => $v){
						$conditions[] = $k . ' = \'' . str_replace('\'', '\'\'', $v) . '\'';
					}
				}
				else
				{
					$conditions = array($req['conditions']);
				}
			}
			else
			{
				$conditions = array('true');
			}
			
			$sql .= ' WHERE ' . implode(' AND ', $conditions);

			return $this->query($sql);
		}

		/**
		* Fonction permettant la suppression de données
		* @var $conds : conditions de la suppression
		* @var $conds['champ'] = 'valeur' ou $conds = 'champ = valeur'
		**/
		public function delete($conds = array())
		{
			if(!empty($conds))
			{
				if(is_array($conds))
				{
					$conditions = array();
					foreach($conds as $k => $v){
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
