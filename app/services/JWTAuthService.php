<?php
/**
 *
 */


namespace app\services;


use DateTimeImmutable;
use DateTimeZone;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use think\exception\ValidateException;

class JWTAuthService
{
    /** @var Configuration */
    protected $config;

    protected $token;

    protected $time;

    public function __construct()
    {
        $this->configInit();
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }


    /**
     * 初始化配置
     * @throws \Exception
     */
    public function configInit()
    {
        $this->time   = new DateTimeImmutable('now',new DateTimeZone('Asia/Shanghai'));
        ini_set('date.timezone','Asia/Shanghai');
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
        return $this->config->builder(ChainedFormatter::withUnixTimestampDates())
            // Configures the issuer (iss claim)
            ->issuedBy('http://example.com')
            // Configures the audience (aud claim)
            ->permittedFor('http://example.org')
            // Configures the id (jti claim)
            ->identifiedBy('4f1g23a12aa')
            // Configures the time that the token was issue (iat claim)
            ->issuedAt($this->time)
            // Configures the time that the token can be used (nbf claim)
            ->canOnlyBeUsedAfter($this->time->modify('+1 minute'))
            // Configures the expiration time of the token (exp claim)
            ->expiresAt($this->time->modify('+1 hour'))
            // Configures a new claim, called "uid"
            ->withClaim('uid', 1)
            // Configures a new header, called "foo"
            ->withHeader('foo', 'bar')
            // Builds a new token
            ->getToken($this->config->signer(), $this->config->signingKey())
            ->toString();
    }

    /**
     * @param null $token
     * @return \Lcobucci\JWT\Token
     */
    public function parserToken($token = null)
    {
        return $token = $this->config->parser()->parse($token??$this->token);
    }

    public function auth()
    {
        $authData =  $this->parserToken($this->token)->claims()->all();

        $timeZone = new DateTimeZone('Asia/Shanghai');

        foreach ($authData as &$authDatum){
            if ($authDatum instanceof DateTimeImmutable){
                $authDatum = $authDatum->setTimezone($timeZone);
            }
        }
        if (($this->parserToken($this->token)->isExpired($this->time))){
            return false;
        }
        return $authData;
    }

    public function parserRequestHeaderToken($bearerToken)
    {
        if (!$bearerToken){
            throw new ValidateException('已过期');
        }
        $tokenInfo = explode(" ",$bearerToken);
        if (!isset($tokenInfo[1])){
            throw new ValidateException('已过期');
        }
        if (strtolower($tokenInfo[0]) !== 'bearer'){
            throw new ValidateException('已过期');
        }
        $this->setToken($tokenInfo[1]);
        return $this;
    }

}