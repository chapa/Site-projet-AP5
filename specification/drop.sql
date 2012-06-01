DROP TRIGGER IF EXISTS TR_episodeWatched ON EpisodesWatched;
DROP FUNCTION IF EXISTS episodeWatched();
DROP TRIGGER IF EXISTS TR_episodeNotWatched ON EpisodesWatched;
DROP FUNCTION IF EXISTS episodeNotWatched();
DROP TRIGGER IF EXISTS TR_seasonWatched ON SeasonsWatched;
DROP FUNCTION IF EXISTS seasonWatched();
DROP TRIGGER IF EXISTS TR_supprUserSerie ON Watch;
DROP FUNCTION IF EXISTS supprUserSerie();
DROP TRIGGER IF EXISTS TR_supprUser ON Users;
DROP FUNCTION IF EXISTS supprUser();

DROP TABLE IF EXISTS SeriesWatched CASCADE;
DROP TABLE IF EXISTS SeasonsWatched CASCADE;
DROP TABLE IF EXISTS EpisodesWatched CASCADE;
DROP TABLE IF EXISTS Watch CASCADE;
DROP TABLE IF EXISTS Users CASCADE;
DROP TABLE IF EXISTS Episodes CASCADE;
DROP TABLE IF EXISTS Seasons CASCADE;
DROP TABLE IF EXISTS Series CASCADE;

DROP TYPE IF EXISTS t_status;