	-- Ajouter un épisode comme vu entraine la mise à jour de l'avancement de la série
CREATE FUNCTION episodeWatched() RETURNS trigger AS'
DECLARE
	x float;
	y float;
	z float;
BEGIN

	x := (	SELECT COUNT(*) FROM EpisodesWatched
			WHERE user_id = new.user_id AND episode_id IN (	SELECT id FROM Episodes
															WHERE season_id = (	SELECT season_id FROM Episodes
																				WHERE id = new.episode_id)));
	y := (	SELECT COUNT(*) FROM Episodes
			WHERE season_id = (	SELECT season_id FROM Episodes
								WHERE id = new.episode_id));
	z := (x / y) * 100;

	IF (SELECT COUNT(*) FROM SeasonsWatched
		WHERE user_id = new.user_id AND season_id = (SELECT season_id FROM Episodes WHERE id = new.episode_id)) = 0 then
		INSERT INTO SeasonsWatched VALUES (new.user_id, (SELECT season_id FROM Episodes WHERE id = new.episode_id), z);
	ELSE
		UPDATE SeasonsWatched SET progression = z
		WHERE user_id = new.user_id AND season_id = (SELECT season_id FROM Episodes WHERE id = new.episode_id);
	END IF;

	RETURN new;
END;' LANGUAGE 'plpgsql';
CREATE TRIGGER TR_episodeWatched AFTER INSERT
ON EpisodesWatched FOR EACH ROW
EXECUTE PROCEDURE episodeWatched();

	-- Supprimer un épisode comme vu entraine la mise à jour de l'avancement de la série (même que l'autre sauf pour delete)
CREATE FUNCTION episodeNotWatched() RETURNS trigger AS'
DECLARE
	x float;
	y float;
	z float;
BEGIN

	x := (	SELECT COUNT(*) FROM EpisodesWatched
			WHERE user_id = old.user_id AND episode_id IN (	SELECT id FROM Episodes
															WHERE season_id = (	SELECT season_id FROM Episodes
																				WHERE id = old.episode_id)));
	y := (	SELECT COUNT(*) FROM Episodes
			WHERE season_id = (	SELECT season_id FROM Episodes
								WHERE id = old.episode_id));
	z := (x / y) * 100;

	IF (SELECT COUNT(*) FROM SeasonsWatched
		WHERE user_id = old.user_id AND season_id = (SELECT season_id FROM Episodes WHERE id = old.episode_id)) = 0 then
		INSERT INTO SeasonsWatched VALUES (old.user_id, (SELECT season_id FROM Episodes WHERE id = old.episode_id), z);
	ELSE
		UPDATE SeasonsWatched SET progression = z
		WHERE user_id = old.user_id AND season_id = (SELECT season_id FROM Episodes WHERE id = old.episode_id);
	END IF;

	RETURN old;
END;' LANGUAGE 'plpgsql';
CREATE TRIGGER TR_episodeNotWatched AFTER DELETE
ON EpisodesWatched FOR EACH ROW
EXECUTE PROCEDURE episodeNotWatched();

	-- Modifier la progression d'une saison entraine la mise à jour de la progression de la série
CREATE FUNCTION seasonWatched() RETURNS trigger AS'
DECLARE
	x float;
BEGIN

	x := (	SELECT AVG(progression) FROM SeasonsWatched
			WHERE user_id = new.user_id AND season_id IN (	SELECT id FROM Seasons
															WHERE serie_id = (	SELECT serie_id FROM Seasons
																				WHERE id = new.season_id)));

	IF (SELECT COUNT(*) FROM SeriesWatched
		WHERE user_id = new.user_id AND serie_id = (SELECT serie_id FROM Seasons WHERE id = new.season_id)) = 0 then
		INSERT INTO SeriesWatched VALUES (new.user_id, (SELECT serie_id FROM Seasons WHERE id = new.season_id), x);
	ELSE
		UPDATE SeriesWatched SET progression = x
		WHERE user_id = new.user_id AND serie_id = (SELECT serie_id FROM Seasons WHERE id = new.season_id);
	END IF;

	RETURN new;
END;' LANGUAGE 'plpgsql';
CREATE TRIGGER TR_seasonWatched AFTER INSERT OR UPDATE
ON SeasonsWatched FOR EACH ROW
EXECUTE PROCEDURE seasonWatched();

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
