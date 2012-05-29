	-- Supprimer un utilisateur implique la suppression des séries qu'il regardait
CREATE FUNCTION supprUser() RETURNS trigger AS'
BEGIN
	
	DELETE FROM Watch
	WHERE user_id = old.id;
	DELETE FROM Watched
	WHERE user_id = old.id;

	RETURN old;
END;' LANGUAGE 'plpgsql';
CREATE TRIGGER TR_supprUser BEFORE DELETE
ON Users FOR EACH ROW
EXECUTE PROCEDURE supprUser();