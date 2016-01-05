<?php

namespace App\Model\ActionQueue;

use App\Company;

/**
 * Action Command Receiver to Suspend Company
 */
class ActionCommandSuspendCompanyReceiver
{

    private function checkParameters(array $params, array $requiredParams)
    {
        foreach ($requiredParams as $key) {
            if(!array_key_exists($key, $params)) {
                throw new \Exception('Required parameter missing ('.$key.') in '.__CLASS__ .'.');
            }
        }
    }

    /**
     * @return company  The return value is suspended Company instance.
     */
    public function methodToInvoke($params)
    {
        // check does all parameters are sent
        $requiredParams = ['companyId'];

        $this->checkParameters($params, $requiredParams);

        $company = Company::findOrFail($params['companyId']);
        $company->suspend();
        $company->save();

        return $company;
    }
}
