CREATE TYPE t_status AS ENUM ('Banni', 'Membre', 'Administrateur');

CREATE TABLE Series
(
	id 						integer,
	title					varchar(255),
	originalTitle			varchar(255),
	synopsis				text,
	nbSeasons				numeric(3),
	nbEpisodes				numeric(3),
	creators				varchar(255),
	actors					varchar(255),
	yearstart				numeric(4),
	yearend					numeric(4),
	mark					numeric(2,1),
	formatTime				numeric(3),
	type					varchar(255),
	nationality 			varchar(255),
	nbwatched				integer				DEFAULT 0,

	primary key (id)
);

CREATE TABLE Seasons
(
	id						integer,
	num 					numeric(3),
	nbEpisodes				numeric(3),
	yearstart				numeric(4),
	yearend 				numeric(4),
	mark					numeric(2,1),
	nbwatched				integer				DEFAULT 0,
	serie_id				integer,

	primary key (id),
	foreign key (serie_id) references Series(id)
);

CREATE TABLE Episodes
(
	id						integer,
	num 					numeric(3),
	title 					varchar(255),
	originalTitle			varchar(255),
	synopsis 				text,
	mark 					numeric(2,1),
	nbwatched  				integer				DEFAULT 0,
	season_id				integer,

	primary key (id),
	foreign key (season_id) references Seasons(id)
); 


CREATE TABLE Users
(
	id 						serial,
	username				varchar(255),
	password				varchar(256),
	mail					varchar(255),
	status					t_status			DEFAULT 'Membre',
	created					timestamp			DEFAULT NOW(),
	lastlogin 				timestamp			DEFAULT NOW(),

	primary key (id)
);

CREATE TABLE Watch
(
	user_id					integer,
	serie_id				integer,

	primary key (user_id, serie_id),
	foreign key (user_id) references Users(id),
	foreign key (serie_id) references Series(id)
);

CREATE TABLE EpisodesWatched
(
	user_id					integer,
	episode_id				integer,

	primary key (user_id, episode_id),
	foreign key (user_id) references Users(id),
	foreign key (episode_id) references Episodes(id)
);

CREATE TABLE SeasonsWatched
(
	user_id					integer,
	season_id				integer,
	progression				numeric(3)			CHECK (progression >= 0 AND progression <= 100) DEFAULT 0,

	primary key (user_id, season_id),
	foreign key (user_id) references Users(id),
	foreign key (season_id) references Seasons(id)
);

CREATE TABLE SeriesWatched
(
	user_id					integer,
	serie_id				integer,
	progression				numeric(3)			CHECK (progression >= 0 AND progression <= 100) DEFAULT 0,

	primary key (user_id, serie_id),
	foreign key (user_id) references Users(id),
	foreign key (serie_id) references Series(id)
);
