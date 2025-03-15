/**
 * Fonction qui demande la suppression d'un conducteur sachant qu'on
 * appelle cette fonction avec
 * @param {int} co_id l'identifiant du conducteur
 * @param {string} co_nom le nom du conducteur
 * @returns false si l'utilisateur ne désire pas supprimer le conducteur, sinon
 *   redirection vers la route de suppression
 */

function conducteur_supprimer(co_id, co_nom){
    // demande la suppression du conducteur grâce à l'apparition
    // d'une fenêtre pop-up
    console.log('ID:', co_id, 'Nom:', co_nom);
    const user_answer = confirm('Voulez vous supprimer le conducteur ' + co_nom + ' ?');

    // Si on a répondu oui, on appelle la route conducteur/supprimer/{id}
    // où {id} est remplacé par l'identifiant du conducteur
    if(user_answer === true){
        // delete
        window.location.replace('/conducteur/supprimer/'+co_id)
    }
    return false;
}

/**
 * Fonction qui réalise l'appel pour la modification d'un conducteur par
 * appel de la route /conducteur/modifier/{id}
 * @param {int} co_id identifiant du conducteur
 * @returns true
 */

function conducteur_modifier(co_id){
    window.location.replace('/conducteur/modifier/'+co_id)

    return true;
}