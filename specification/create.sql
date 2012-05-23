CREATE TABLE Series
(
	id 						integer,
	slug					varchar(255),
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
	nbwatched				integer,

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
	nbwatched				integer,
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
	nbwatched  				integer,
	season_id				integer,

	primary key (id),
	foreign key (season_id) references Seasons(id)
); 


CREATE TABLE Users
(
	id 						integer,
	username				varchar(255),
	password				varchar(256),
	mail					varchar(255),
	created					timestamp,
	lastlogin 				timestamp,

	primary key (id)
);

CREATE TABLE Watch
(
	user_id					integer,
	episode_id				integer,
	watched					boolean,

	primary key (episode_id,user_id),
	foreign key (user_id) references Users(id),
	foreign key (episode_id) references Episodes(id)
);
