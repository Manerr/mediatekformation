<?php
namespace App\Controller;

use App\Repository\FormationRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AccueilController
 *
 * @author emds
 */
class AccueilController extends AbstractController{
    
    /**
     * @var FormationRepository
     */
    private $repository;
    private $categorieRepository;
    
    /**
     * 
     * @param FormationRepository $repository
     */
    public function __construct(FormationRepository $repository,CategorieRepository $categ) {
        $this->repository = $repository;
        $this->categorieRepository = $categ;
    }   
    
    #[Route('/', name: 'accueil')]
    public function index(): Response{

            
        setlocale(LC_TIME, 'fr_FR.UTF-8');
        \Locale::setDefault('fr_FR');
            
        $formations = $this->repository->findAllLasted(2);

        $categoriesByFormations = $this->categorieRepository->findCategoriesOrderByNbFormationsDesc();



        return $this->render("pages/accueil.html.twig", [
            'formations' => $formations,
            'categoriesByFormations' => $categoriesByFormations
        ]); 
    }

    #[Route('/', name: 'default.accueil')]
    public function def_index(): Response{

        setlocale(LC_TIME, 'fr_FR.UTF-8');
        \Locale::setDefault('fr_FR');

        $formations = $this->repository->findAllLasted(2);
        return $this->render("pages/accueil.html.twig", [
            'formations' => $formations
        ]); 
    }


    
    #[Route('/cgu', name: 'cgu')]
    public function cgu(): Response{
        return $this->render("pages/cgu.html.twig"); 
    }
}
