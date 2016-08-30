<?php

namespace LoginCidadao\CoreBundle\Handler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use LoginCidadao\CoreBundle\Entity\AccessSession;

class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler
{
    /** @var EntityManager */
    private $em;

    /**
     * Constructor
     * @param HttpUtils $httpUtils
     * @param EntityManager $em
     * @param array $options
     */
    public function __construct(
        HttpUtils $httpUtils,
        EntityManager $em,
        $options
    ) {
        parent::__construct($httpUtils, $options);
        $this->em = $em;
    }

    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from AbstractAuthenticationListener.
     * @param Request $request
     * @param TokenInterface $token
     * @return Response The response to return
     */
    function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $form = $request->get('login_form_type');
        if (isset($form['username'])) {
            $vars = array(
                'ip' => $request->getClientIp(),
                'username' => $form['username'],
            );
            $accessSession = $this->em->getRepository('LoginCidadaoCoreBundle:AccessSession')->findOneBy($vars);
            if (!$accessSession) {
                $accessSession = new AccessSession();
                $accessSession->fromArray($vars);
            }
            $accessSession->setVal(0);
            $this->em->persist($accessSession);
            $this->em->flush();
        }

        // CPF check
        if ($token->getUser()->isCpfExpired()) {
            return $this->httpUtils->createRedirectResponse($request, 'lc_registration_cpf');
        }

        if (strstr($token->getUser()->getUsername(), '@') !== false) {
            return $this->httpUtils->createRedirectResponse($request, 'lc_update_username');
        }

        return parent::onAuthenticationSuccess($request, $token);
    }
}
