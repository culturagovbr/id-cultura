<?php

namespace LoginCidadao\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use LoginCidadao\APIBundle\Entity\ActionLogRepository;

class ActivityLogController extends Controller
{

    /**
     * @Route("/activity/list", name="lc_activity_log_list")
     * @Template()
     */
    public function listAction()
    {
        $repo = $this->getActionLogRepository();
        $logs = $repo->getWithClientByPerson($this->getUser(), 50);

        return compact('logs');
    }

    /**
     * @return ActionLogRepository
     */
    private function getActionLogRepository()
    {
        return $this->getDoctrine()->getRepository('LoginCidadaoAPIBundle:ActionLog');
    }
}
