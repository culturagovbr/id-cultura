<?php
/**
 * This file is part of the login-cidadao project or it's bundles.
 *
 * (c) Guilherme Donato <guilhermednt on github>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LoginCidadao\RemoteClaimsBundle\Model;

use LoginCidadao\CoreBundle\Entity\Authorization;
use LoginCidadao\CoreBundle\Model\PersonInterface;
use LoginCidadao\OAuthBundle\Model\ClientInterface;

interface RemoteClaimManagerInterface
{
    /**
     * @param RemoteClaimAuthorizationInterface $authorization
     * @return RemoteClaimAuthorizationInterface
     */
    public function enforceAuthorization(RemoteClaimAuthorizationInterface $authorization);

    /**
     * @param TagUri|string $claimName
     * @param PersonInterface $person
     * @param ClientInterface $client
     * @return bool
     */
    public function isAuthorized($claimName, PersonInterface $person, ClientInterface $client);

    /**
     * @param Authorization $authorization
     * @return bool
     */
    public function revokeAllAuthorizations(Authorization $authorization);

    /**
     * Removes TagURI scopes from the given input.
     *
     * @param string|array $scope
     * @return string|array Returns the input with TagURI scopes removed. The type will remain the same as the input.
     */
    public function filterRemoteClaims($scope);

    /**
     * @param Authorization $authorization
     * @return RemoteClaimInterface[]
     */
    public function getRemoteClaimsFromAuthorization(Authorization $authorization);

    /**
     * @param Authorization $authorization
     * @return RemoteClaimAuthorizationInterface[]
     */
    public function getRemoteClaimsAuthorizationsFromAuthorization(Authorization $authorization);

    /**
     * The response will be in the format:
     * [
     *   [
     *     'authorization' => RemoteClaimAuthorizationInterface,
     *     'remoteClaim' => RemoteClaimInterface,
     *   ],
     * ]
     * @param ClientInterface $client
     * @param PersonInterface $person
     * @return array
     */
    public function getRemoteClaimsWithTokens(ClientInterface $client, PersonInterface $person);

    /**
     * @param ClaimProviderInterface $claimProvider
     * @param string $accessToken
     * @return RemoteClaimAuthorizationInterface
     */
    public function getRemoteClaimAuthorizationByAccessToken(ClaimProviderInterface $claimProvider, $accessToken);
}
