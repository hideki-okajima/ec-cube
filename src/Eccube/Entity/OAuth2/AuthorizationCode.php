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

/**
 * AuthorizationCode
 *
 * @ORM\Table(
 *     name="dtb_oauth2_authorization_code",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="code", columns={"code"})}
 * )
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Eccube\Repository\OAuth2\AuthorizationCodeRepository")
 */
class AuthorizationCode extends \Eccube\Entity\AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @var integer
     *
     * @ORM\Column(name="client_id", type="integer", options={"unsigned":true})
     */
    private $client_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=true, options={"unsigned":true})
     */
    private $user_id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expires", type="datetimetz")
     */
    private $expires;

    /**
     * @var string
     *
     * @ORM\Column(name="redirect_uri", type="string", length=255)
     */
    private $redirect_uri;

    /**
     * @var string
     *
     * @ORM\Column(name="scope", type="string", nullable=true, length=4000)
     */
    private $scope;

    /**
     * @var string
     *
     * @ORM\Column(name="id_token", type="string", nullable=true, length=4000)
     */
    private $id_token;

    /**
     * @var \Eccube\Entity\OAuth2\Client
     *
     * @ORM\ManyToOne(targetEntity="Eccube\Entity\OAuth2\Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     * })
     */
    private $client;

    /**
     * @var \Eccube\Entity\OAuth2\OpenID\UserInfo
     *
     * @ORM\ManyToOne(targetEntity="Eccube\Entity\OAuth2\OpenID\UserInfo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return AuthorizationCode
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set client_id
     *
     * @param integer $clientId
     *
     * @return AuthorizationCode
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
     * Set user_id
     *
     * @param integer $userId
     *
     * @return AuthorizationCode
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get user_id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set expires
     *
     * @param \DateTime $expires
     *
     * @return AuthorizationCode
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;

        return $this;
    }

    /**
     * Get expires
     *
     * @return \DateTime
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Set redirect_uri
     *
     * @param string $redirectUri
     *
     * @return AuthorizationCode
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirect_uri = $redirectUri;

        return $this;
    }

    /**
     * Get redirect_uri
     *
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirect_uri;
    }

    /**
     * Set scope
     *
     * @param string $scope
     *
     * @return AuthorizationCode
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get scope
     *
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Set client
     *
     * @param \Eccube\Entity\OAuth2\Client $client
     *
     * @return AuthorizationCode
     */
    public function setClient(\Eccube\Entity\OAuth2\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \Eccube\Entity\OAuth2\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set user
     *
     * @param \Eccube\Entity\OAuth2\OpenID\UserInfo $user
     *
     * @return AuthorizationCode
     */
    public function setUser(\Eccube\Entity\OAuth2\OpenID\UserInfo $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Eccube\Entity\OAuth2\OpenID\UserInfo
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set id_token
     *
     * @param string $idToken
     *
     * @return AuthorizationCode
     */
    public function setIdToken($idToken)
    {
        $this->id_token = $idToken;

        return $this;
    }

    /**
     * Get id_token
     *
     * @return string
     */
    public function getIdToken()
    {
        return $this->id_token;
    }
}
