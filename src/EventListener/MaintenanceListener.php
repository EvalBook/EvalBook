<?php
namespace App\EventListener;
use App\Entity\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


class MaintenanceListener
{
    private $maintenance;
    private $ipAuthorized;
    private $engine;
    private $em;

    /**
     * MaintenanceListener constructor.
     * @param $maintenance
     * @param Environment $engine
     * @param EntityManagerInterface $em
     */
    public function __construct($maintenance, Environment $engine, EntityManagerInterface $em)
    {
        $this->maintenance = $maintenance["status"];
        $this->ipAuthorized = $maintenance["ipAuthorized"];
        $this->engine = $engine;
        $this->em = $em;
    }


    /**
     * @param RequestEvent $event
     */
    public function displayMaintenance(RequestEvent $event)
    {
        // If maintenance mode and ip is not authorized, then displaying the right template.
        if($this->maintenance && !in_array($_SERVER['REMOTE_ADDR'], array_values($this->ipAuthorized))) {
            // Checking first the bundled maintenance value.
            $this->setMaintenance($event);
        }
        else {
            $repository = $this->em->getRepository(Configuration::class);

            // Getting maintenance mode status.
            $maintenance = $repository->findOneBy([
                'name' => 'maintenance'
            ]);

            // Getting authorized ip addresses.
            $authorizedIp = $repository->findOneBy([
                'name' => 'ipAuthorized'
            ]);

            // Checking maintenance status.
            if(!is_null($maintenance) && boolval($maintenance->getValue())) {
                // Checking if ip address is allowed to bypass de maintenance mode.
                if($event->getRequest()->getClientIp() !== $authorizedIp->getValue()) {
                    $this->setMaintenance($event);
                }
            }
        }
    }


    /**
     * Load maintenance template if needed.
     * @param RequestEvent $event
     */
    private function setMaintenance(RequestEvent $event)
    {
        $err = 'An error occurred defining the EvalBook maintenance state';
        try {
            $maintenanceTemplate = $this->engine->render('maintenance/index.html.twig');
            // We send our response with a 503 response code (service unavailable)
            $event->setResponse(new Response($maintenanceTemplate, 503));
        }
        catch (LoaderError $e) {
            throw new NotFoundHttpException($err);
        }
        catch (RuntimeError $e) {
            throw new NotFoundHttpException($err);
        }
        catch (SyntaxError $e) {
            throw new NotFoundHttpException($err);
        }

        $event->stopPropagation();
    }
}