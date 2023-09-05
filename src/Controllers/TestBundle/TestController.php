<?php

namespace Controllers\TestBundle;

use Controllers\TestBundle\forms\TestFormType;
use Core\Abstracts\Controller;
use Core\Db;
use Core\Logger;
use Core\Request;
use Exceptions\QueryException;
use Model\Test\Test;

class TestController extends Controller
{
    public function testAction(Request $request, Logger $logger)
    {
        $sampleService = $this->get('service.test_service');
        $sampleService->sample();

        $testRoute = $this->getRouter()->generateRoute('routes_test_add', ['id' => 2]);
        $test = Test::getInstance();
        $form = $this->createForm(TestFormType::class, $test);

        $form->handleRequest($request);

        try {

            if ($form->isSubmitted() && $form->isValid()) {
                Db::beginTransaction();
                $test::insert();
                Db::commit();
            }
        } catch (\Exception $e) {
            Db::rollback();
        }

        return $this->render('Index/index.php', [
            'pageTitle' => 'Sample',
            'name' => 'Albert',
            'testlink' => $testRoute,
            'form' => $form->createView()
        ]);
    }

    public function addAction(Request $request)
    {
    }

    public function editAction(Request $request)
    {
    }
}