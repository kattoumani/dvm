<?php
namespace App\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
 
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;
 
/**
 * Contrôleur global
 */
class GeneralController extends AbstractController
{
    // connexion à la base de données
    private $connexion;
 
    /**
     * Constructeur prenant en paramètre la connextion à la base de données
     */
    public function __construct(Connection $connexion)
    {
        $this->connexion = $connexion;
    }
 
    /**
     * Route / qui pointe sur index.php
     */
    #[Route('/', name: 'index')]
    public function homepage(Request $request,
        EntityManagerInterface $entityManager) : Response {
 
        return $this->render("base.html.twig");
 
    }
 
    /**
     * Route qui permet de supprimer toutes les tables de la base de données
     * cette route est utilisée pour des tests et le débogage
     */
    #[Route('/drop/tables', name: 'drop_tables')]
    public function drop_tables(Request $request,
        EntityManagerInterface $entityManager) : Response {
 
        // Execute the SQL statement
        $this->connexion->executeStatement('DROP TABLE IF EXISTS equipement_vehicule');
        $this->connexion->executeStatement('DROP TABLE IF EXISTS vehicule');
        $this->connexion->executeStatement('DROP TABLE IF EXISTS equipement');
        $this->connexion->executeStatement('DROP TABLE IF EXISTS conducteur');
 
        //
        // On aurait pu utiliser le code suivant pour afficher
        // la page index
        //
        // return $this->render("base.html.twig");
        //
        // Cependant il est préférable d'utiliser le code ci-dessous, car sinon
        // c'est la route /drop/tables qui est gardée au sein de l'URL
        //
       
        return $this->redirectToRoute('index');
       
    }

    

}

?>