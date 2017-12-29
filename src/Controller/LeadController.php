<?php


namespace Controller;

use Form\LeadValidation;
use Symfony\Component\HttpFoundation\Request;

class LeadController extends AbstractController
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function submitLead(Request $request)
    {
        $leadData = new LeadValidation();
        $leadForm = $this->getFormFactory()->create(\Form\LeadType::class, $leadData);
        $leadForm->handleRequest($request);
        $db = $this->app['dbs']['form_input'];
        $getResponse = new \GetResponse('040d0c895ee80235db1fb1b8539d5a25');

        if (!$leadForm->isSubmitted() && !$leadForm->isValid()) {
            return 'Please check your input data';
        }

        $db->executeUpdate("
            INSERT INTO form_input(name, email, phone) 
            VALUES('$leadData->name', '$leadData->email', '$leadData->phone')
        ");

        $gr = $getResponse->addContact([
            'name' => $leadData->name,
            'email' => $leadData->email,
            'campaign' => array('campaignId' => '4XSmp')
        ]);

        return $this->render('thankyou.html.twig', [
            'name' => $leadData->name,
            'email' => $leadData->email,
            'phone' => $leadData->phone,
            'gr' => $gr->message
        ]);
    }
}
