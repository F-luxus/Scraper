<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LaunchCrawler extends Command
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:launch-crawler';

    protected function configure()
    {
        // ...
    }

protected function execute(InputInterface $input, OutputInterface $output)
{
        $output->writeln('Pradedamas duomenų nuskaitymas');
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
                $em = $this->em;
                $repository = $em->getRepository(Scraper::class);
                $pavadinimas = $data['title'];
                
                $product = $repository->findOneBy([
                    'title' => $pavadinimas,
                ]);    
                if(!$product) 
                {
                    $image_file = file_get_contents($data['image']);
                    $image_name = explode("/",$data['image']);
                    $img_name=$image_name[count($image_name)-1];
                    file_put_contents("./public/images/$img_name", $image_file);
                           
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
    $output->writeln('!!!! DUOMENYS SURINKTI');

    return 0;
}
}