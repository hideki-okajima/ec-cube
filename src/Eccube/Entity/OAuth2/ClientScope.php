<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eccube\Entity\OAuth2;

use Doctrine\ORM\Mapping as ORM;

if (!class_exists('\Eccube\Entity\OAuth2\ClientScope')) {
    /**
     * ClientScope
     *
     * @ORM\Table(name="dtb_oauth2_client_scope")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\OAuth2\ClientScopeRepository")
     */
    class ClientScope extends \Eccube\Entity\AbstractEntity
    {
        /**
         * @var int
         *
         * @ORM\Column(name="client_id", type="integer", options={"unsigned":true})
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="NONE")
         */
        private $client_id;

        /**
         * @var int
         *
         * @ORM\Column(name="scope_id", type="integer", options={"unsigned":true})
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="NONE")
         */
        private $scope_id;

        /**
         * @var \Eccube\Entity\OAuth2\Client
         *
         * @ORM\ManyToOne(targetEntity="Eccube\Entity\OAuth2\Client")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="client_id", referencedColumnName="id")
         * })
         */
        private $Client;

        /**
         * @var \Eccube\Entity\OAuth2\Scope
         *
         * @ORM\ManyToOne(targetEntity="Eccube\Entity\OAuth2\Scope")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="scope_id", referencedColumnName="id")
         * })
         */
        private $Scope;

        /**
         * Set client_id
         *
         * @param integer $clientId
         *
         * @return ClientScope
         */
        public function setClientId($clientId)
        {
            $this->client_id = $clientId;

            return $this;
        }

        /**
         * Get client_id
         *
         * @return integer
         */
        public function getClientId()
        {
            return $this->client_id;
        }

        /**
         * Set scope_id
         *
         * @param integer $scopeId
         *
         * @return ClientScope
         */
        public function setScopeId($scopeId)
        {
            $this->scope_id = $scopeId;

            return $this;
        }

        /**
         * Get scope_id
         *
         * @return integer
         */
        public function getScopeId()
        {
            return $this->scope_id;
        }

        /**
         * Set Client
         *
         * @param \Eccube\Entity\OAuth2\Client $client
         *
         * @return ClientScope
         */
        public function setClient(\Eccube\Entity\OAuth2\Client $client = null)
        {
            $this->Client = $client;

            return $this;
        }

        /**
         * Get Client
         *
         * @return \Eccube\Entity\OAuth2\Client
         */
        public function getClient()
        {
            return $this->Client;
        }

        /**
         * Set Scope
         *
         * @param \Eccube\Entity\OAuth2\Scope $scope
         *
         * @return ClientScope
         */
        public function setScope(\Eccube\Entity\OAuth2\Scope $scope = null)
        {
            $this->Scope = $scope;

            return $this;
        }

        /**
         * Get Scope
         *
         * @return \Eccube\Entity\OAuth2\Scope
         */
        public function getScope()
        {
            return $this->Scope;
        }
    }
}
