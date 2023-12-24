<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given Je suis un visiteur
     */
    public function jeSuisUnVisiteur()
    {
        $this->visit('http://localhost/migration/');
       
    }

    /**
     * @When Je visite la page d'accueil du site
     */
    public function jeVisiteLaPageDaccueilDuSite()
    {
        $this->visitPath('/');
    }

    /**
     * @Then Je devrais voir le feed Instagram incorporé
     */
    public function jeDevraisVoirLeFeedInstagramIncorpore()
    {

        $this->assertPageContainsText('Instagram');
        
    }

    /**
     * @When Je visite le site
     */
    public function jeVisiteLeSite()
    {
        $this->visitPath('/');
    }

    /**
     * @When Je fais défiler la page vers le bas
     */
    public function jeFaisDefilerLaPageVersLeBas()
    {
        $this->visitPath('/#colophon');
    }

    /**
     * @Then Je devrais voir toutes les informations disponibles
     */
    public function jeDevraisVoirToutesLesInformationsDisponibles()
    {

        $infos = array( 'erreur', 'épreuves', 'inscriptions', 'contact', 
        'médias', 'triathlon', 'swimrun', 'aquathlon', 'pratique', 'accès', 'hébergement', 
        'village', 'éco-responsabilité', 'merci à eux');
        foreach($infos as $info){
            $this->assertPageContainsText($info);
        }

        
    }

    /**
     * @When Je clique sur le menu de navigation :arg1
     */
    public function jeCliqueSurLeMenuDeNavigation()
    {
        
    $path = '//a[contains(@href, "?page_id=596")]';
    $moreInfos = $this->getSession()->getPage()->find('xpath', $path);
    $moreInfos->click();
    }


    /**
     * @Then Je devrais être redirigé vers la section des informations de la page
     */
    public function jeDevraisEtreRedirigeVersLaSectionDesInformationsDeLaPage()
    {
        $expectedUrl = 'http://localhost/migration/?page_id=596';  

    $currentUrl = $this->getSession()->getCurrentUrl();

    if ($currentUrl !== $expectedUrl) {
        throw new \RuntimeException(sprintf(
            'Expected to be redirected to "%s", but current URL is "%s"',
            $expectedUrl,
            $currentUrl
        ));
    }
    }

    /**
     * @Then Je devrais voir la liste des cours disponibles
     */
    public function jeDevraisVoirLaListeDesCoursDisponibles()
    {
        $infos = array( 'triathlon', 'swimrun', 'aquathlon');
        foreach($infos as $info){
            $this->assertPageContainsText($info);
        }
    }

    /**
 * @Then Chaque cours devrait afficher le titre, la description, et des liens pertinents
 */
public function chaqueCoursDevraitAfficherLeTitreLaDescriptionEtDesLiensPertinents()
{
    $expectedUrl = $this->locatePath('/?page_id=596');
    $actualUrl = $this->getSession()->getCurrentUrl();
    if ($expectedUrl !== $actualUrl) {
        throw new \RuntimeException("Expected to be redirected to $expectedUrl, but got $actualUrl.");
    }
}

    /**
     * @When Je visite une page qui n'existe pas
     */
    public function jeVisiteUnePageQuiNexistePas()
    {
        $this->visitPath('/une-page-qui-nexiste-pas');
    }

   /**
 * @Then Je devrais recevoir une réponse avec un code d'erreur :arg1
 */
public function jeDevraisRecevoirUneReponseAvecUnCodeDerreur($arg1)
{
    $expectedStatusCode = (int) $arg1;
    $actualStatusCode = $this->getSession()->getStatusCode();

    if ($actualStatusCode !== $expectedStatusCode) {
        throw new \RuntimeException(
            sprintf('Expected status code %d but received %d', $expectedStatusCode, $actualStatusCode)
        );
    }
}
}
