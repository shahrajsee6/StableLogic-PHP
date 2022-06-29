<?php

namespace App\Controller;

use App\Entity\Continent;
use App\Entity\Country;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class DataController extends AbstractController
{
    #[Route('/importAndSaveData', name: 'import_save_data')]
    public function index(PersistenceManagerRegistry $doctrine): BinaryFileResponse
    {
        $em = $doctrine->getManager();
        $file = "data/SQL Import.xlsx";
        $spreadSheet =  \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadSheet, "Xlsx");
       
        $this->importAndSaveContinentData($em, $spreadSheet);
        $this->importAndSaveCountryData($em, $spreadSheet);

        $writer->save($file);

        $response = new BinaryFileResponse($file);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'Exported_file.xlsx'
        );

        return $response;
    }

    private function importAndSaveContinentData($em, $spreadSheet)
    {   
        $continentData = $spreadSheet->getSheetByName('Continent');
        
        $i = 2; //Start reading from 2nd row
        while(!empty($name = $continentData->getCell('B'.$i)->getValue()))
        {
            $continent = $em->getRepository(Continent::class)->findOneBy(['name' =>  $name]);
            if (!$continent) {

                $continent = new Continent();
                $continent->setName($name);

                $sql = $continent->getInsertQuery();
                $em->persist($continent);
                $em->flush();

                $id = $continent->getId();

                $continentData->setCellValue('A'.$i, $id);
                $continentData->setCellValue('C'.$i, $sql);
            }
            $i++;
        }
    }

    private function importAndSaveCountryData($em, $spreadSheet)
    {   
        $countryData = $spreadSheet->getSheetByName('Country');
        
        $i = 2; //Start reading from 2nd row
        while(!empty($name = $countryData->getCell('B'.$i)->getValue()))
        {
            $country = $em->getRepository(Country::class)->findOneBy(['name' =>  $name]);
            if (!$country) {

                $continent = $em->getRepository(Continent::class)->findOneBy(['name' =>  $countryData->getCell('C'.$i)->getValue()]);
                $alpha2Code = trim($countryData->getCell('E'.$i)->getValue());
                $currencyCode = trim($countryData->getCell('F'.$i)->getValue());

                $country = new Country();

                $country->setName($name);
                $country->setContinent($continent);
                $country->setAlpha2Code($alpha2Code);
                $country->setCurrencyCode($currencyCode);

                $sql = $country->getInsertQuery();
                $em->persist($country);
                $em->flush();

                $id = $country->getId();
                
                $countryData->setCellValue('A'.$i, $id);
                $countryData->setCellValueExplicit('D'.$i, "=INDEX(Continent!A2:B4, MATCH(C$i,Continent!B2:B4,0),1)", DataType::TYPE_FORMULA);
                $countryData->setCellValue('G'.$i, $sql);
            }
            $i++;
        }
    }
}
