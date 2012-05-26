<?php $title_for_layout = ($user['id'] == $_SESSION['user']['id']) ? 'Modification de votre profil' : 'Modification du profil de ' . $user['username']; ?>
<?php debug($user); ?>