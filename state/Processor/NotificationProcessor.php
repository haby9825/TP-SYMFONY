php 
<?php 
namespace App\State; 
use ApiPlatform\Metadata\Operation; 
use ApiPlatform\State\ProcessorInterface; 
use App\Entity\Rendu; 
use App\Entity\Notification; 
use Doctrine\ORM\EntityManagerInterface; 
use ApiPlatform\Doctrine\Common\State\PersistProcessor; 
use Symfony\Component\DependencyInjection\Attribute\Autowire; 
class RenduProcessor implements ProcessorInterface 

{ 
    public function __construct( 
        #[Autowire(service: PersistProcessor::class)] 
        private ProcessorInterface $persistProcessor, 
        private EntityManagerInterface $entityManager 
    ) { 

    } 

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []) 
    { 
        $result = $this->persistProcessor->process($data, $operation, $uriVariables, $context); 

        if ($data instanceof Documents && $operation instanceof \ApiPlatform\Metadata\Post) { 
            $notification = new Notification(); 
            $nomEns = $data->getEnseignant() ? $data->getEnseignant()->getNom() : 'Inconnu'; 
            $notification->setMessage("$nomEns a déposé une nouvelle consigne : " . $data->getNom()); 
            $notification->setDate(new \DateTime()); 
            
            $this->entityManager->persist($notification); 
            $this->entityManager->flush(); 
        } 
        return $result; 
    } 
} 