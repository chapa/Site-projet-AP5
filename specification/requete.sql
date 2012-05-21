--page d'accueil
--selectioner toue les series pour les afficher (episodes a voir)
select id,title,num,numi
from series,seasons,episodes,users
where 