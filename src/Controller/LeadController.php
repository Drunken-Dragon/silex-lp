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

        if ($leadForm->isSubmitted() && $leadForm->isValid()) {
            $db->executeUpdate("INSERT INTO form_input(name, email, phone) VALUES('$leadData->name', '$leadData->email', '$leadData->phone')");
        }

        return '<h1>Records added</h1><br>';
    }
}
