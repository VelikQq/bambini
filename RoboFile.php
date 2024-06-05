<?php
require_once 'vendor/autoload.php';

class RoboFile extends \Robo\Tasks
{
    use \Codeception\Task\MergeReports;
    use \Codeception\Task\SplitTestsByGroups;

//    public function parallelSplitTests()
//    {
//        // Split your tests by files
//        $this->taskSplitTestFilesByGroups(3)
//            ->projectRoot('.')
//            ->testsFrom(['tests/acceptance', 'tests/atomic', 'tests/payments'])
//            ->groupsTo('tests/_data/paracept_')
//            ->run();
//    }

    public function parallelRun($suites)
    {
        $result = '';
        $parallel = $this->taskParallelExec();
        //$tests = file("tests/_data/paracept_1");

//        foreach ($suites as $suite) {
//            for ($i = 1; $i <= 2; $i++) {
//                $tests = file("tests/_data/paracept_$i");
//                for ($i = 1; $i <= 2; $i++) {
//
//                    foreach ($tests as $test) {
//                        if (str_contains($test, $suite)) {
//                            unset($tests[array_search($test, $tests)]);
//                            $test = str_replace('\\', '', strrchr($test, '\\'));
//                            $parallel->process(
//                                $this->taskCodecept()// use built-in Codecept task
//                                ->option("--steps")
//                                    ->option("--debug")
//                                    ->env("env")
//                                    //->group("paracept")// for all paracept_* groups
//                                    ->suite($suite)// run tests
//                                    ->test($test)
//                                    ->xml($test . "_result.xml") // save XML results
//                            );
//
//
//                        }
//                    }
//                    $result = $parallel->run();
//
//                }
//            }
//        }


        foreach ($suites as $suite) {
            for ($i = 1; $i <= 3; $i++) {
                $parallel->process(
                    $this->taskCodecept()// use built-in Codecept task
                        ->suite($suite)// run tests
                        ->env("env")
//                        ->options(["--steps", "--debug"])
                        ->option("--steps")
                        ->option("--debug")
                        ->group("paracept_$i")// for all paracept_* groups
                        ->xml("result_$i.xml") // save XML results
                );
            }
            $result = $parallel->run();
        }

        return $result;
    }

    public function parallelMergeResults($suites)
    {
//        $merge = $this->taskMergeXmlReports();
//
////        foreach ($suites as $suite) {
//            for ($i = 1; $i <= 3; $i++) {
//                $merge->from("tests/_output/result_$i.xml");
//            }
////        }
//
//        $merge->into("tests/_output/allure-results/result_paracept.xml")->run();

        $this->taskMergeXmlReports()
            ->from('tests/_output/result_1.xml')
            ->from('tests/_output/result_2.xml')
            ->from('tests/_output/result_3.xml')
            ->into('tests/_output/allure-results/result_paracept.xml')
            ->run();
    }

    function parallelAll()
    {
        $suites = ['acceptance', 'atomic', 'payments'];

//        $this->parallelSplitTests();
        $result = $this->parallelRun($suites);
        $this->parallelMergeResults($suites);

        return $result;
    }
}