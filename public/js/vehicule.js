function vehicule_supprimer(ve_id, ve_marque, ve_modele){
    // demande la suppression du conducteur grâce à l'apparition
    // d'une fenêtre pop-up
    console.log('ID:', ve_id);
    const user_answer = confirm('Voulez vous supprimer le véhicule ' + ve_id + ' ' + ve_marque + ' ' + ve_modele + ' ?');

    // Si on a répondu oui, on appelle la route conducteur/supprimer/{id}
    // où {id} est remplacé par l'identifiant du conducteur
    if(user_answer === true){
        // delete
        window.location.replace('/vehicule/supprimer/'+ve_id)
    }
    return false;
}

function vehicule_modifier(ve_id){
    window.location.replace('/vehicule/modifier/'+ve_id)

    return true;
}