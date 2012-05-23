CREATE TABLE Series
(
	id 				numeric(3) ,
	slug			varchar(100),
	title			varchar(100),
	originalTitle	varchar(100),
	synopsis		text,
	nbSeasons		numeric(2),
	nbEpisodes		numeric(3),
	creator			varchar(30),
	actor			text,
	yearStart		numeric(4),
	yearEnd			numeric(4),
	mark			numeric(1),
	formatTime		numeric(1),
	type			varchar(30),
	nationality 	varchar(30),
	numwatched		numeric(6),
	primary key (id)
);

CREATE TABLE Seasons
(
	id				numeric(5),
	num 			numeric(2),
	nbEpisodess		numeric(2),
	yearStart		numeric(2),
	yearEnd 		numeric(2),
	mark			numeric(1),
	numwatched		numeric(6),
	idS				numeric(3),
	primary key (id),
	foreign key (idS) references Series(id)
);

CREATE TABLE Episodes
(
	id				numeric(5),
	num 			numeric(2),
	title 			varchar(100),
	originalTitle	text,
	synopsis 		text,
	mark 			numeric(1),
	watched			boolean,
	numwatched  	numeric(6),
	idS				numeric(5),
	primary key (id),
	foreign key (idS) references Seasons(id)
); 


CREATE TABLE Users
(
	id 				numeric(5),
	username		varchar(20),
	password		varchar(256),
	mail			varchar(50),
	created			date,
	lastlogin 		date,
	primary key (id)
);

CREATE TABLE Watch
(
	idU				numeric(5),
	idE				numeric(5),
	primary key (idE,idU),
	foreign key (idU) references Users(id),
	foreign key (idE) references Episodes(id)
);
