<?php

/*
 * This file is part of the Black package.
 *
 * (c) Alexandre Balmes <albalmes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Black\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class AdminConfigController
 *
 * @Route("/admin/config")
 *
 * @package Black\Bundle\UserBundle\Controller
 * @author  Alexandre Balmes <albalmes@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
class AdminConfigController extends Controller
{
    /**
     * @Route("/user.html", name="admin_config_user")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     *
     * @return array
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function userAction()
    {
        $documentManager    = $this->getManager();
        $document           = $documentManager->findPropertyByName('User');

        if (!$document) {
            throw $this->createNotFoundException('The property User does not exist');
        }

        $formHandler    = $this->get('black_user.config.form.handler');
        $process        = $formHandler->process($document);

        if ($process) {
            $documentManager->updateProperty($document);

            return $this->redirect($this->generateUrl('admin_config_user'));
        }

        return array(
            'document'  => $document,
            'form'      => $formHandler->getForm()->createView()
        );
    }

    /**
     * @return DocumentManager
     */
    protected function getManager()
    {
        return $this->get('black_config.manager.config');
    }
}
