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
 * RefreshToken
 *
 * @ORM\Table(
 *     name="dtb_oauth2_refresh_token",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="refresh_token", columns={"refresh_token"})}
 * )
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Eccube\Repository\OAuth2\RefreshTokenRepository")
 */
class RefreshToken extends \Eccube\Entity\AbstractEntity
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
     * @ORM\Column(name="refresh_token", type="string", length=255)
     */
    private $refresh_token;

    /**
     * @var integer
     *
     * @ORM\Column(name="client_id", type="integer", options={"unsigned":true})
     */
    private $client_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=true , options={"unsigned":true})
     */
    private $user_id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expires", type="datetimetz")
     */
    private $expires;

    /**
     * @var string|null
     *
     * @ORM\Column(name="scope", type="string", length=4000, nullable=true)
     */
    private $scope;

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
     * Set refresh_token
     *
     * @param string $refreshToken
     *
     * @return RefreshToken
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refresh_token = $refreshToken;

        return $this;
    }

    /**
     * Get refresh_token
     *
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refresh_token;
    }

    /**
     * Set client_id
     *
     * @param integer $clientId
     *
     * @return RefreshToken
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
     * @return RefreshToken
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
     * @return RefreshToken
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
     * Set scope
     *
     * @param string $scope
     *
     * @return RefreshToken
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
     * @return RefreshToken
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
     * @return RefreshToken
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
}
