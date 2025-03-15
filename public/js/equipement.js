function equipement_supprimer(eq_id, eq_libelle){
    // demande la suppression du conducteur grâce à l'apparition
    // d'une fenêtre pop-up
    const user_answer = confirm("Voulez vous supprimer l'équipement " + eq_libelle + ' ?');

    // Si on a répondu oui, on appelle la route conducteur/supprimer/{id}
    // où {id} est remplacé par l'identifiant du conducteur
    if(user_answer === true){
        // delete
        window.location.replace('/equipement/supprimer/'+eq_id)
    }
    return false;
}

function equipement_modifier(eq_id){
    window.location.replace('/equipement/modifier/'+eq_id)

    return true;
}