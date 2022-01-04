<?php
/**
 *
 */


namespace app\services;


use DateTimeImmutable;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class JWTAuthService
{
    /** @var Configuration */
    protected $config;

    protected $token;

    public function __construct()
    {
        $this->configInit();
    }

    /**
     * 初始化配置
     */
    public function configInit()
    {
        $this->config = Configuration::forSymmetricSigner(
        // You may use any HMAC variations (256, 384, and 512)
            new Sha256(),
            // replace the value below with a key of your own!
            InMemory::plainText(config('jwt.private_key'))
        // You may also override the JOSE encoder/decoder if needed by providing extra arguments here
        );
    }

    /**
     * 生成token
     * @return string token
     */
    public function token(){
        $now   = new DateTimeImmutable();
        return $this->config->builder()
            // Configures the issuer (iss claim)
            ->issuedBy('http://example.com')
            // Configures the audience (aud claim)
            ->permittedFor('http://example.org')
            // Configures the id (jti claim)
            ->identifiedBy('4f1g23a12aa')
            // Configures the time that the token was issue (iat claim)
            ->issuedAt($now)
            // Configures the time that the token can be used (nbf claim)
            ->canOnlyBeUsedAfter($now->modify('+1 minute'))
            // Configures the expiration time of the token (exp claim)
            ->expiresAt($now->modify('+1 hour'))
            // Configures a new claim, called "uid"
            ->withClaim('uid', 1)
            // Configures a new header, called "foo"
            ->withHeader('foo', 'bar')
            // Builds a new token
            ->getToken($this->config->signer(), $this->config->signingKey())->toString();
    }

    public function parserToken($token = null)
    {
        return $token = $this->config->parser()->parse($token??$this->token);
    }

}