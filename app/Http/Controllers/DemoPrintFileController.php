<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


//*********************************
// IMPORTANT NOTE 
// ==============
// If your website requires user authentication, then
// THIS FILE MUST be set to ALLOW ANONYMOUS access!!!
//
//*********************************

//Includes WebClientPrint classes

use App\Neodynamic\WebClientPrint;
use App\Neodynamic\Utils;
use App\Neodynamic\DefaultPrinter;
use App\Neodynamic\InstalledPrinter;
use App\Neodynamic\PrintFile;
use App\Neodynamic\PrintFilePDF;
use App\Neodynamic\PrintFileTXT;
use App\Neodynamic\PrintRotation;
use App\Neodynamic\PrintOrientation;
use App\Neodynamic\TextAlignment;
use App\Neodynamic\ClientPrintJob;

use Session;

class DemoPrintFileController extends Controller
{
    public function index(){

        $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('DemoPrintFileController@printFile'), Session::getId());    

        return view('DemoPrintFile.index', ['wcpScript' => $wcpScript]);
    }

    public function printFile(Request $request){
        
       if ($request->exists(WebClientPrint::CLIENT_PRINT_JOB)) {

            $useDefaultPrinter = ($request->input('useDefaultPrinter') === 'checked');
            $printerName = urldecode($request->input('printerName'));
            $filetype = $request->input('filetype');
            $fileName = uniqid() . '.' . $filetype;

            $filePath = '';
            if ($filetype === 'PDF') {
                $filePath = public_path().'/files/LoremIpsum.pdf';
            } else if ($filetype === 'TXT') {
                $filePath = public_path().'/files/LoremIpsum.txt';
            } else if ($filetype === 'DOC') {
                $filePath = public_path().'/files/LoremIpsum.doc';
            } else if ($filetype === 'XLS') {
                $filePath = public_path().'/files/SampleSheet.xls';
            } else if ($filetype === 'JPG') {
                $filePath = public_path().'/files/penguins300dpi.jpg';
            } else if ($filetype === 'PNG') {
                $filePath = public_path().'/files/SamplePngImage.png';
            } else if ($filetype === 'TIF') {
                $filePath = public_path().'/files/patent2pages.tif';
            }else if ($filetype === 'TXTCUSTOM') {
                $filePath = public_path().'/files/struk.txt';
            }

            if (!Utils::isNullOrEmptyString($filePath)) {
                //Create a ClientPrintJob obj that will be processed at the client side by the WCPP
                $cpj = new ClientPrintJob();
                
                if ($filetype === 'PDF')
                {
                    $myfile = new PrintFilePDF($filePath, $fileName, null);
                    $myfile->printRotation = PrintRotation::None;
                    //$myfile->pagesRange = '1,2,3,10-15';
                    //$myfile->printAnnotations = true;
                    //$myfile->printAsGrayscale = true;
                    //$myfile->printInReverseOrder = true;
                    $cpj->printFile = $myfile;
                }
                else if ($filetype === 'TXT' OR $filetype === 'TXTCUSTOM')
                {
                    $myfile = new PrintFileTXT($filePath, $fileName, null);
                    $myfile->printOrientation = PrintOrientation::Portrait;
                    $myfile->fontName = 'Arial';
                    $myfile->fontSizeInPoints = 12;
                    //$myfile->textColor = '#ff00ff';
                    //$myfile->textAlignment = TextAlignment::Center;
                    //$myfile->fontBold = true;
                    //$myfile->fontItalic = true;
                    //$myfile->fontUnderline = true;
                    //$myfile->fontStrikeThrough = true;
                    //$myfile->marginLeft = 1; // INCH Unit!!!
                    //$myfile->marginTop = 1; // INCH Unit!!!
                    //$myfile->marginRight = 1; // INCH Unit!!!
                    //$myfile->marginBottom = 1; // INCH Unit!!!
                    $cpj->printFile = $myfile;
                }
                else
                {
                    $cpj->printFile = new PrintFile($filePath, $fileName, null);
                }    

				if ($useDefaultPrinter || $printerName === 'null') {
                    $cpj->clientPrinter = new DefaultPrinter();
                } else {
                    $cpj->clientPrinter = new InstalledPrinter($printerName);
                }

                //Send ClientPrintJob back to the client
                return response($cpj->sendToClient())
                            ->header('Content-Type', 'application/octet-stream');
                
            }
        }
    }    

    
}
