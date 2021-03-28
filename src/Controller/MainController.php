<?php
namespace App\Controller;

use App\Entity\Report;
use App\Form\ReportType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
       return $this->render('index.html.twig', [
           'years' => null,
       ]);
    }

    /**
     * @Route("/year", name="main_files", methods={"GET", "POST"})
     */
    public function setFiles(Request $request): Response
    {
//        echo '<pre>';
//        print_r($request);
//        echo '</pre>';
//        die();
        if ($_FILES && $_FILES["filename"]["error"] == UPLOAD_ERR_OK) {
            $path = 'assets/files/' . $_FILES["filename"]["name"];
            move_uploaded_file($_FILES["filename"]["tmp_name"], $path);

//        считываем csv файлы в массив
            $dataCsv = [];
            if (($handle = fopen($path, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    array_push($dataCsv, $data);
                }
                fclose($handle);
            }

//            обработка файла csv
            if ($request->request->get('source') == 'Всемирной организации интеллектуальной собственности') {
                array_splice($dataCsv, 0, 6);
                array_splice($dataCsv, 1, 1);

                $json = json_encode($dataCsv);
                $file = fopen('data.json','w+');
                fwrite($file, $json);
                fclose($file);
//                echo '<pre>';
//                print_r($dataCsv[0]);
//                echo '</pre>';
//                die();
                return $this->render('index.html.twig', [
                    'years' => $dataCsv[0],
                ]);
            } elseif ($request->request->get('source') == 'WorldBank'){
                array_splice($dataCsv, 0, 4);
                array_splice($dataCsv[0], 0, 4);
                $json = json_encode($dataCsv);
                $file = fopen('data.json','w+');
                fwrite($file, $json);
                fclose($file);

                return $this->render('index.html.twig', [
                    'years' => $dataCsv[0],
                ]);
            }
            // удаляем файл после использования, чтобы не плодить
            unlink($path);
        }
        return $this->render('index.html.twig', [
            'years' => null,
        ]);
    }

//    /**
//     * @Route("/get_year", name="get_year", methods={"GET", "POST"})
//     */
//    public function getYear(Request $request): Response
//    {
//        echo $request->request->get('year');
//    }

    /**
     * @Route("/file", name="json_files", methods={"GET", "POST"})
     */
    public function jsonFile(Request $request): Response
    {
        $year = $_GET['year'];
        $json = json_decode(file_get_contents('data.json'), true);
//        echo '<pre>';
//        print_r($json);
//        echo '</pre>';
//        die();
        $index = array_search($year, $json[0]);
        array_splice($json[0],0,$index);
        array_splice($json[0],1,count($json[0]));
        for($i=1;$i<count($json); $i++) {
            if($index != 4)
                array_splice($json[$i], 4,$index-4);
            array_splice($json[$i], 5,count($json[$i]));
            $json[$i][0] = str_replace(' ', '_', $json[$i][0]);
//            if(count($json[$i]) > 4 )
//                array_splice($json, (count($json)-1), 1);
//        }
        }
        for($i=1; $i<count($json); $i++) {
            if ($json[$i][4] == "")
                $json[$i][4] = 0;
        }
//        echo '<pre>';
//        print_r($json);
//        echo '</pre>';
//        die();
        // нормализация
        // минимимум
        $min = $json[1][4];
        for($i=2;$i<count($json); $i++) {
            if ($min > $json[$i][4])
                $min = $json[$i][4];
        }
        // максимум
        $max = $json[1][4];
        for($i=2;$i<count($json); $i++) {
            if($max < $json[$i][4])
                $max = $json[$i][4];
        }
        // нормализация
        for($i=1;$i<count($json); $i++) {
            $json[$i][5] = round(($json[$i][4] - $min)/($max - $min),3) * 100;
            $json[$i][6] = round($json[$i][5]* 250 / 100);
        }
//        echo '<pre>';
//        print_r($json);
//        echo '</pre>';
//        die();
        echo json_encode($json);
        exit();
    }
}