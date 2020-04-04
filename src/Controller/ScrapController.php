<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Scraper;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DomCrawler\Crawler;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;


class ScrapController extends AbstractController
{
    private $entityManager;   
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/scrap", name="scrap")
     */
    public function index(Request $request)
    {

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://truckmanualshub.com/category/trucks/');
        $html= ''.$response->getBody();
        $crawler = new Crawler($html);
        $nodeValues = $crawler->filter('.pagination > ul > li')->each(function (Crawler $node, $i) {
                
            $pages=$node->text();
        
            $data = new \GuzzleHttp\Client();
            $page = $data->request("GET", "https://truckmanualshub.com/category/trucks/page/$pages");
            $codes= ''.$page->getBody();
            $crawler = new Crawler($codes);
            $nodeValues = $crawler->filter('#content > article')->each(function (Crawler $nodes, $i) {
                    
            $titles = $nodes->filter('h2')->text();
            $titles = trim($titles );
            $images = $nodes->filter('img')->attr('src');
            $descriptions = $nodes->filter('p')->text();
            $descriptions =str_replace(" Read More »", "", $descriptions);
            $categories = $nodes->filter('.categories')->text();
            $data = [
                'title' => $titles,
                'description' => $descriptions,
                'image' => $images,
                'category' => $categories];
            
            return $data;            
            });                
            foreach ($nodeValues as $data) {
                $repository = $this->getDoctrine()->getRepository(Scraper::class);
                $pavadinimas = $data['title'];
                
                $product = $repository->findOneBy([
                    'title' => $pavadinimas,
                ]);    
                if(!$product) 
                {
                    $image_file = file_get_contents($data['image']);
                    $image_name = explode("/",$data['image']);
                    $img_name=$image_name[count($image_name)-1];
                    file_put_contents("images/$img_name", $image_file);
                    $em = $this->getDoctrine()->getManager();        
                    $database = new Scraper();
                    $database->setTitle($data['title']); //Respective entity methods
                    $database->setDescription($data['description']); //Respective entity methods
                    //$database->setPhoto($data['image']); //Respective entity methods
                    $database->setPhoto("images/$img_name"); //Respective entity methods
                    $database->setCategory($data['category']); //Respective entity methods
                    $em->persist($database);

                }    
            }
        if(!$product){$em->flush();}
        });
        echo "Duomenys perkelti į DB<br>";
        return $this->render('scrap.html.twig', []);
    }
}
