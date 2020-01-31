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

namespace Eccube\Repository\OAuth2;

use Eccube\Entity\OAuth2\Client;
use Eccube\Repository\AbstractRepository;
use OAuth2\Storage\ClientCredentialsInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * ClientRepository
 *
 * @see http://bshaffer.github.io/oauth2-server-php-docs/cookbook/doctrine2/
 */
class ClientRepository extends AbstractRepository implements ClientCredentialsInterface
{
    /**
     * ClientRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Client::class);
    }

    /**
     * Client ID を指定して Client のフィールドの配列を取得します.
     *
     * @param string $clientIdentifier Client ID の文字列
     *
     * @return object
     */
    public function getClientDetails($clientIdentifier)
    {
        $client = $this->findOneBy(['client_identifier' => $clientIdentifier]);
        if ($client) {
            $client = $client->toArray();
        }

        return $client;
    }

    /**
     * Client ID と Client secret の妥当性をチェックします.
     *
     * @param string $clientIdentifier Client ID の文字列
     * @param string $clientSecret Client secret の文字列
     *
     * @return boolean 妥当な場合 true
     */
    public function checkClientCredentials($clientIdentifier, $clientSecret = null)
    {
        $client = $this->findOneBy(['client_identifier' => $clientIdentifier]);
        if ($client) {
            return $client->verifyClientSecret($clientSecret);
        }

        return false;
    }

    /**
     * 使用可能な認可タイプかどうかをチェックします.
     *
     * 以下の認可タイプ(grant_type)が使用可能です
     * - refresh_token
     * - authorization_code
     * - implicit
     *
     * @param string $clientId Client ID の文字列
     * @param string $grantType 認可タイプ(grant_type)の文字列
     *
     * @return boolean 使用可能な認可タイプの場合 true
     */
    public function checkRestrictedGrantType($clientId, $grantType)
    {
        $alloewdGrantTypes = ['refresh_token', 'authorization_code', 'implicit'];

        return in_array($grantType, $alloewdGrantTypes);
    }

    /**
     * Public Client かどうか.
     *
     * このメソッドは今のところ、常に false を返します.
     *
     * @param string $clientId Client ID の文字列
     *
     * @return boolean Public Client の場合 true
     */
    public function isPublicClient($clientId)
    {
        return false;
    }

    /**
     * Client が使用可能な scope 文字列をスペース区切りで返します.
     *
     * @param string $clientId Client ID の文字列
     *
     * @return string スペース区切りの使用可能な scope 文字列
     */
    public function getClientScope($clientId)
    {
        $client = $this->findOneBy(['client_identifier' => $clientId]);
        if ($client) {
            $scopes = $client->getScopeAsArray();

            return implode(' ', $scopes);
        }

        return null;
    }
}
