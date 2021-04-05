<?php

namespace Ticket\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Ticket\Http\Requests;
use Ticket\Http\Controllers\Controller;

use Exception;
use Mail;

class EmailController extends Controller
{
    public function php_info() {
		echo phpinfo();
	}

	function getBody($uid, $imap) {
	    $body = $this->get_part($imap, $uid, "TEXT/HTML");
	    // if HTML body is empty, try getting text body
	    if ($body == "") {
	        $body = $this->get_part($imap, $uid, "TEXT/PLAIN");
	    }
	    return $body;
	}

	function get_part($imap, $uid, $mimetype, $structure = false, $partNumber = false) {
	    if (!$structure) {
	           $structure = imap_fetchstructure($imap, $uid, FT_UID);
	    }
	    if ($structure) {
	        if ($mimetype == $this->get_mime_type($structure)) {
	            if (!$partNumber) {
	                $partNumber = 1;
	            }
	            $text = imap_fetchbody($imap, $uid, $partNumber, FT_UID);
		
	            switch ($structure->encoding) {
	                case 3: return imap_base64($text);
	                case 4: return imap_qprint($text);
	                default: return $text;
	           }
	       }
	
	        // multipart 
	        if ($structure->type == 1) {
	            foreach ($structure->parts as $index => $subStruct) {
	                $prefix = "";
	                if ($partNumber) {
	                    $prefix = $partNumber . ".";
	                }
	                $data = $this->get_part($imap, $uid, $mimetype, $subStruct, $prefix . ($index + 1));
	                if ($data) {
						return $data;
					}
	            }
	        }
	    }
	    return false;
	}

	function get_mime_type($structure) {
	    $primaryMimetype = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");
	
	    if ($structure->subtype) {
	       return $primaryMimetype[(int)$structure->type] . "/" . $structure->subtype;
	    }
	    return "TEXT/PLAIN";
	}	

	function getFilenameFromPart($part) {

		$filename = '';
		if($part->ifdparameters) {
			foreach($part->dparameters as $object) {
				if(strtolower($object->attribute) == 'filename') {
					$filename = $object->value;
				}
			}
		}
	
		if(!$filename && $part->ifparameters) {
			foreach($part->parameters as $object) {
				if(strtolower($object->attribute) == 'name') {
					$filename = $object->value;
				}
			}
		}	
		return $filename;
	}
	
	function flattenParts($messageParts, $flattenedParts = array(), $prefix = '', $index = 1, $fullPrefix = true) {

		foreach($messageParts as $part) {
			$flattenedParts[$prefix.$index] = $part;
			if(isset($part->parts)) {
				if($part->type == 2) {
					$flattenedParts = $this->flattenParts($part->parts, $flattenedParts, $prefix.$index.'.', 0, false);
				}
				elseif($fullPrefix) {
					$flattenedParts = $this->flattenParts($part->parts, $flattenedParts, $prefix.$index.'.');
				}
				else {
					$flattenedParts = $this->flattenParts($part->parts, $flattenedParts, $prefix);
				}
				unset($flattenedParts[$prefix.$index]->parts);
			}
			$index++;
		}
		return $flattenedParts;			
	}

	public function	storeFile($fileName,$ticket_ID) {
		$file = $request->file('file-up');
		$extension = $file->getClientOriginalExtension();
		$fileName = $file->getFilename().'.'.$extension;
		$origName = $file->getClientOriginalName();
		$mime = $file->getClientMimeType();
		$file->move("uploads", $fileName);
        if ($request->ticket_ID){
            $id = DB::table('uploads')->insertGetId(['filename' => $fileName,
                                                    'origname' => $origName,
                                                    'mime'=> $mime,
                                                    'type'=> $request->type,
                                                    'connected_ticket'=>$request->ticket_ID]);
            DB::table('tickets')->where('ticket_ID',$request->ticket_ID)
                                ->update(['modified'=>date('Y-m-d H:i:s')]);
        }		
	}
	
	public function getMails() {
		$uid = (!empty($_GET["uid"])) ? $_GET["uid"] : 0;

// --- connect to IMAP ---

		$imap = imap_open("{mail.negypolus.hu:143/imap}", "hibajegy@negypolus.hu", "hibajegyJ1");
	// ---- Ellenőrízni, van e új levél ----- 
		$numMessages = imap_num_msg($imap);
		if ($numMessages) {
			for ($i = $numMessages; $i > 0; $i--) {
			    $header = imap_header($imap, $i);

			    $fromInfo = $header->from[0];
			    $replyInfo = $header->reply_to[0];
				$toInfo = $header->to[0];
	
			    $details = array(
			        "fromAddr" => (isset($fromInfo->mailbox) && isset($fromInfo->host))
			            ? $fromInfo->mailbox . "@" . $fromInfo->host : "",
			        "fromName" => (isset($fromInfo->personal))
			            ? $fromInfo->personal : "",
			        "toAddr" => (isset($toInfo->mailbox) && isset($toInfo->host))
			            ? $toInfo->mailbox . "@" . $toInfo->host : "",
			        "subject" => (isset($header->subject))
			            ? $header->subject : "",
			        "udate" => (isset($header->udate))
			            ? $header->udate : ""
			    );

			    $uid = imap_uid($imap, $i);
				$content = $this->getBody($uid,$imap);
			
				$utf8 = false;
// -----  Check  Windows code page              
				if ((substr($details["fromName"], 2, 5) == "UTF-8") or (substr($details["subject"], 2, 5) == "UTF-8")){
					$utf8= true;
				}
				if ((substr($details["fromName"], 2, 5) == "utf-8") or (substr($details["subject"], 2, 5) == "utf-8")){
					$utf8= true;
				}
                if (substr($details["subject"], 0, 7) == "=?utf-8") {
                    $utf8= true;
                }
                
                if (preg_match("//u", $content)) {
                    $utf8 = true;
                }                
				if (!$utf8) {
					$content = mb_decode_mimeheader($content);
				}
		
				$details["subject"] = str_replace("_"," ",mb_decode_mimeheader($details["subject"]));
		//  -------- Html szűrés -------
				$textcontent = strip_tags($content);
		//  -------- Üres sorok törlése ------
				$textcontent = str_replace('&nbsp;', ' ', $textcontent);
				imap_errors();
				imap_alerts();
		//  -------- Ellenőrzés, hogy létező ticketre válaszol-e  ----------
				$ticket_number = substr($details["subject"],strpos($details["subject"],'#')+1, 5);
				if (is_numeric($ticket_number) and (strlen($ticket_number) == 5)) {
					$affected_rows = DB::table('tickets')->where('ticket_ID', $ticket_number)->update(['ticket_state'=>'Folyamatban', 'modified'=>date('Y-m-d H:i:s')]);
					if ($affected_rows > 0) {						
						$id = DB::table('comments')->insertGetId([
							'comment' => $textcontent,
							'user_ID' => 1,
							'ticket_ID' => $ticket_number,
							'comment_type' => 'public',
						]);
			// ---- Email küldés felelősnek ------
			
						$subject = "Hozzászóltak a #".$ticket_number . " számú hibajegyhez. ";
						$ticket = DB::table('tickets')->select('partner_ID','owner')->where('ticket_ID',$ticket_number)->first();
						
						$owners_email = DB::table('users')->select('email')->where('id', $ticket->owner)->first();
					    if (isset($owners_email)) {
							Mail::queue('emails.newTicketMail', ['title' => '#'.$ticket_number . ' számú hibajegyhez hozzászóltak', 'content' => $content], function ($message) use ($owners_email,$subject) {
								$message->to($owners_email->email);
								$message->subject($subject);
							});
						}
            // ---- Visszaigazolás a hozzászólást beküldőnek ------
                        $toAddress = $details["fromAddr"];
                        $subject = "Hozzászólás a #".$ticket_number." sorszámú hibajegyhez - IT Szerviz HelpDesk";
                        $content = "Köszönettel vettük hozzászólását. Ez egy automata üzenet.<br>
                                További információ információ megtekintése érdekében amennyiben rendelkezik jogosultsággal,  ide kattintva léphet be hibajegy és feladatkezelő rendszerünkbe: https://ticket.negypolus.hu
                                <br/><br/>Üdvözlettel:<br/>IT Szerviz HelpDesk";
                        Mail::queue('emails.newTicketMail', ['title' => "Tisztelt Ügyfelünk!", 'content' => $content], function ($message) use ($toAddress,$subject) {
                        		$message->to($toAddress);
                        		$message->subject($subject);
                        });				
					}
				}
		//  --------  Új ticket mail beolvasása	 ------------
				foreach ($header->to as $to ) {
			        $toAddr = ((isset($to->mailbox) && isset($to->host))
			            ? $to->mailbox . "@" . $to->host : "");

				$partner = DB::table('partners')->select('partner_ID')->where('partner_state','active')->where('email',$toAddr)->first();
				if (isset($partner->partner_ID)) {
//					$owner = DB::table('responsible')->where('partner_ID',$partner->partner_ID)->first();
					$id = DB::table('tickets')->insertGetId([
					    'partner_ID' => $partner->partner_ID,
					    'created_by' => 1,
					    'title' => $details["subject"],
					    'content' => $textcontent,
						'source' => 'e-mail',
						'priority' => 'Normál',
						'reply_address' => $details["fromAddr"],
					]);
					$ticket_ID = $id;
					
			// ----- csatolások, képek kezelése ----- 
					$structure = imap_fetchstructure($imap, $uid, FT_UID);
					if (property_exists($structure,"parts")) {
						$flattenedParts = $this->flattenParts($structure->parts);
//						var_dump($structure);				
						foreach ($flattenedParts as $index=> $part) {
							$decode = imap_fetchbody($imap, $uid, $index, FT_UID); //to get the base64 encoded string for the image
							if ($part->encoding){							
								$data = base64_decode($decode);
							}
							$filename = $this->getFilenameFromPart($part);
							if ($filename) {
								$utf8 = false;
								if ((substr($filename, 2, 5) == "UTF-8")){
									$utf8= true;
								}
								else {
									if ((substr($filename, 0, 2) <> "=?")) {
										$utf8= true;
									}
								}
								if (!$utf8) {
									$origName = mb_decode_mimeheader($filename);
								}
								else {
									$origName = $filename;
								}
							}	
							if (isset($data)) {
								$newFileName = (string)time().$uid.$index;
								switch ($part->subtype) {
									case 'JPEG': 
										$id = DB::table('uploads')->insertGetId(['filename' => $newFileName.'.jpg',
						                                'origname' => $origName,
						                                'mime'=> $part->subtype,
						                                'type'=> 'attachment',
						                                'connected_ticket'=> $ticket_ID]);
										file_put_contents('uploads/'.$newFileName.'.jpg' , $data);								
										break;
									case 'PNG': 
										$id = DB::table('uploads')->insertGetId(['filename' => $newFileName.'.png',
                                                    'origname' => $origName,
                                                    'mime'=> $part->subtype,
                                                    'type'=> 'attachment',
                                                    'connected_ticket'=> $ticket_ID]);
										file_put_contents('uploads/'.$newFileName.'.png' , $data);								
										break;
									case 'TIFF':
										$id = DB::table('uploads')->insertGetId(['filename' => $newFileName.'.tif',
                                                    'origname' => $origName,
                                                    'mime'=> $part->subtype,
                                                    'type'=> 'attachment',
                                                    'connected_ticket'=> $ticket_ID]);
										file_put_contents('uploads/'.$newFileName.'.tif' , $data);								
										break;							
									case 'PDF':
										$id = DB::table('uploads')->insertGetId(['filename' => $newFileName.'.pdf',
                                                    'origname' => $origName,
                                                    'mime'=> $part->subtype,
                                                    'type'=> 'attachment',
                                                    'connected_ticket'=> $ticket_ID]);
										file_put_contents('uploads/'.$newFileName.'.pdf' , $data);								
										break;
									case 'VND.OPENXMLFORMATS-OFFICEDOCUMENT.SPREADSHEETML.SHEET':
										$id = DB::table('uploads')->insertGetId(['filename' => $newFileName.'.xlsx',
                                                    'origname' => $origName,
                                                    'mime'=> $part->subtype,
                                                    'type'=> 'attachment',
                                                    'connected_ticket'=> $ticket_ID]);
										file_put_contents('uploads/'.$newFileName.'.xlsx' , $data);								
										break;
									case 'VND.MS-EXCEL':
										$id = DB::table('uploads')->insertGetId(['filename' => $newFileName.'.xls',
                                                    'origname' => $origName,
                                                    'mime'=> $part->subtype,
                                                    'type'=> 'attachment',
                                                    'connected_ticket'=> $ticket_ID]);
										file_put_contents('uploads/'.$newFileName.'.xls' , $data);								
										break;
									case 'VND.OPENXMLFORMATS-OFFICEDOCUMENT.WORDPROCESSINGML.DOCUMENT':
										$id = DB::table('uploads')->insertGetId(['filename' => $newFileName.'.docx',
                                                    'origname' => $origName,
                                                    'mime'=> $part->subtype,
                                                    'type'=> 'attachment',
                                                    'connected_ticket'=> $ticket_ID]);
										file_put_contents('uploads/'.$newFileName.'.docx' , $data);								
										break;
									case 'MSWORD':
										$id = DB::table('uploads')->insertGetId(['filename' => $newFileName.'.doc',
                                                    'origname' => $origName,
                                                    'mime'=> $part->subtype,
                                                    'type'=> 'attachment',
                                                    'connected_ticket'=> $ticket_ID]);
										file_put_contents('uploads/'.$newFileName.'.doc' , $data);								
										break;
									case ('ZIP' == $part->subtype or $part->subtype == 'X-ZIP-COMPRESSED'):
										$id = DB::table('uploads')->insertGetId(['filename' => $newFileName.'.zip',
                                                    'origname' => $origName,
                                                    'mime'=> $part->subtype,
                                                    'type'=> 'attachment',
                                                    'connected_ticket'=> $ticket_ID]);
										file_put_contents('uploads/'.$newFileName.'.zip' , $data);								
										break;
									case ('OCTET-STREAM'):
										$id = DB::table('uploads')->insertGetId(['filename' => $newFileName.'.7z',
                                                    'origname' => $origName,
                                                    'mime'=> $part->subtype,
                                                    'type'=> 'attachment',
                                                    'connected_ticket'=> $ticket_ID]);
										file_put_contents('uploads/'.$newFileName.'.7z' , $data);								
										break;
								}
							}
						}
					}
			// ------ Logolás: új ticket------
					$partnername = DB::table('partners')->select('partner_name')->where('partner_ID',$partner->partner_ID)->first();
					$IP = "e-mail";
					$msg = "Új hibajegy";
					$id1 = DB::table('logs')->insertGetId([
						   'user_ID' => 1,
						   'ticket_ID' => $id,
						   'partner_ID' => $partner->partner_ID,
						   'action' => $msg,
						   'IP' => $IP]);

			// ------ E-mail küldés a felelősnek -------  Ez levelezésből lett megoldva!!!
/*					$subject = "Új hibajegy - #".$ticket_ID . " ". $partnername->partner_name;
					foreach ($owners as $owner) {                 
					    $owners_email = DB::table('users')->select('email')->where('id', $owner->user_ID)->first();
					    if (isset($owners_email))
						Mail::queue('emails.newTicketMail', ['title' => '#'.$ticket_ID . ' számú hibajegy rögzítésre került', 'content' => $details["subject"]], function ($message) use ($owners_email,$subject) {
								$message->to($owners_email->email);
								$message->subject($subject);
						});
					}
*/
			// ------ E-mail a bejelentés nyugtázására ------
					$toAddress = $details["fromAddr"];
                    $subject = "Értesítés  #".$ticket_ID." sorszámú hibajegy regisztrálásáról - IT Szerviz HelpDesk";
                    $content = "Köszönettel vettük bejelentését, melyet a #".$ticket_ID." sorszámú hibajegyen / feladatban regisztráltunk.<br>A bejelentés tárgya: ".$details["subject"].
                                "<br/>Kollegánk hamarosan felveszi Önnel a kapcsolatot emailben, vagy telefonon. Addig szíves türelmét és megértését kérjük.
                                <br/><br/>Üdvözlettel:<br/>IT Szerviz HelpDesk";
                    Mail::queue('emails.newTicketMail', ['title' => "Tisztelt Ügyfelünk!", 'content' => $content], function ($message) use ($toAddress,$subject) {
							$message->to($toAddress);
							$message->subject($subject);
					});
				}
		// -------  nem azonosított ügyfél kezelése  -------
				else {						
					$fw_message = imap_fetchheader($imap, $i).imap_body($imap, $i);
					$deststream = imap_open("{mail.negypolus.hu:143/imap}", "j@negypolus.hu", "Megane");
					$imapresult = imap_append($deststream,'{mail.negypolus.hu:143/imap}',$fw_message);
					if (!$imapresult) {
						error_log(imap_last_error()."\n", 3, "ticket.log");
					}
					imap_errors();
					imap_alerts();
					imap_close($deststream);
				}
			}
			}
		}
	// ------ Feldolgozott emailek átmozgatása ---------
		if ($numMessages > 0) {
			$imapresult=imap_mail_move($imap,'1:'.$numMessages,'Saved');
			if ($imapresult) {
				error_log(imap_last_error()."\n", 3, "ticket.log");
			}
			$imapresult = imap_expunge($imap);			
			if ($imapresult) {
				error_log(imap_last_error()."\n", 3, "ticket.log");
			}
		}
		
		imap_errors();
 		imap_alerts();
		imap_close($imap);
//		var_dump( $folders);
	}

	public function getMails_test() {
		$uid = (!empty($_GET["uid"])) ? $_GET["uid"] : 0;

// --- connect to IMAP ---

//		$imap = imap_open("{mail.negypolus.hu:143/imap}", "hibajegy@negypolus.hu", "hibajegyJ1");
		$imap = imap_open("{mail.negypolus.hu:143/imap}", "teszt@negypolus.hu", "Valami34");
	// ---- Ellenőrízni, van e új levél ----- 
		$numMessages = imap_num_msg($imap);
        echo 'üzenetek száma: '.$numMessages;
		if ($numMessages) {
            echo 'Van üzenet <br>';
			for ($i = $numMessages; $i > 0; $i--) {
			    $header = imap_header($imap, $i);

			    $fromInfo = $header->from[0];
			    $replyInfo = $header->reply_to[0];
				$toInfo = $header->to[0];
	
			    $details = array(
			        "fromAddr" => (isset($fromInfo->mailbox) && isset($fromInfo->host))
			            ? $fromInfo->mailbox . "@" . $fromInfo->host : "",
			        "fromName" => (isset($fromInfo->personal))
			            ? $fromInfo->personal : "",
			        "toAddr" => (isset($toInfo->mailbox) && isset($toInfo->host))
			            ? $toInfo->mailbox . "@" . $toInfo->host : "",
			        "subject" => (isset($header->subject))
			            ? $header->subject : "",
			        "udate" => (isset($header->udate))
			            ? $header->udate : ""
			    );

			    $uid = imap_uid($imap, $i);
				$content = $this->getBody($uid,$imap);
				$utf8 = false;
// -----  Kód page teszteléshez
                echo "Suject: ".$details["subject"]."<br>";
                echo "fromname: ".$details["fromName"]."<br>";
                echo $content."<br>";
				if ((substr($details["fromName"], 2, 5) == "UTF-8") or (substr($details["subject"], 2, 5) == "UTF-8")){
					$utf8= true;
				}
				if ((substr($details["fromName"], 2, 5) == "utf-8") or (substr($details["subject"], 2, 5) == "utf-8")){
					$utf8= true;
				}
                if (substr($details["subject"], 0, 7) == "=?utf-8") {
                    $utf8= true;
                }
                
// -----  Check  Windows code page              
                if (preg_match("//u", $content)) {
                    $utf8 = true;
                }                
                
				if (!$utf8) {
					$content = mb_decode_mimeheader($content);
				}
		
				$details["subject"] = str_replace("_"," ",mb_decode_mimeheader($details["subject"]));
		//  -------- Html szűrés -------
//				$textcontent = strip_tags($content, '<p><a><br></p><br />');
				$textcontent = strip_tags($content);
		//  -------- Üres sorok törlése ------
				$textcontent = str_replace('&nbsp;', ' ', $textcontent);
//                echo $textcontent."<br>"; 
				imap_errors();
				imap_alerts();
		//  -------- Ellenőrzés, hogy létező ticketre válaszol-e  ----------
				$ticket_number = substr($details["subject"],strpos($details["subject"],'#')+1, 5);
				if (is_numeric($ticket_number) and (strlen($ticket_number) == 5)) {
//					echo $ticket_number. "<br>"; 	// ---- törölni !!! ----
					$affected_rows = DB::table('tickets')->where('ticket_ID', $ticket_number)->update(['ticket_state'=>'Folyamatban', 'modified'=>date('Y-m-d H:i:s')]);
					if ($affected_rows > 0) {						
						$id = DB::table('comments')->insertGetId([
							'comment' => $textcontent,
							'user_ID' => 1,
							'ticket_ID' => $ticket_number,
							'comment_type' => 'public',
						]);
			// ---- Email küldés felelősnek új hozzászólásról ------
			
						$subject = "Hozzászóltak a #".$ticket_number . " számú hibajegyhez. ";
						$ticket = DB::table('tickets')->select('partner_ID','owner')->where('ticket_ID',$ticket_number)->first();
						
						$owners_email = DB::table('users')->select('email')->where('id', $ticket->owner)->first();
					    if (isset($owners_email)) {
							Mail::queue('emails.newTicketMail', ['title' => '#'.$ticket_number . ' számú hibajegyhez hozzászóltak', 'content' => $textcontent], function ($message) use ($owners_email,$subject) {
								$message->to($owners_email->email);
								$message->subject($subject);
							});
						}
            // ---- Visszaigazolás a hozzászólást beküldőnek ------
                        $toAddress = $details["fromAddr"];
                        $subject = "Hozzászólás a #".$ticket_number." sorszámú hibajegyhez - IT Szerviz HelpDesk";
                        $content = "Köszönettel vettük hozzászólását. Ez egy automata üzenet.<br>
                                További információ információ megtekintése érdekében amennyiben rendelkezik jogosultsággal,  ide kattintva léphet be hibajegy és feladatkezelő rendszerünkbe: https://ticket.negypolus.hu
                                <br/><br/>Üdvözlettel:<br/>IT Szerviz HelpDesk";
                        Mail::queue('emails.newTicketMail', ['title' => "Tisztelt Ügyfelünk!", 'content' => $content], function ($message) use ($toAddress,$subject) {
                        		$message->to($toAddress);
                        		$message->subject($subject);
                        });				
                    }
				}
		//  --------  Új ticket mail beolvasása	 ------------
				foreach ($header->to as $to ) {
			        $toAddr = ((isset($to->mailbox) && isset($to->host))
			            ? $to->mailbox . "@" . $to->host : "");

				$partner = DB::table('partners')->select('partner_ID')->where('partner_state','active')->where('email',$toAddr)->first();
		//  --------  ügyfél azonosítás, ha lehetséges --------
				if (isset($partner->partner_ID)) {
					$id = DB::table('tickets')->insertGetId([
					    'partner_ID' => $partner->partner_ID,
					    'created_by' => 1,
					    'title' => $details["subject"],
					    'content' => $textcontent,
						'source' => 'e-mail',
						'priority' => 'Normál',
						'reply_address' => $details["fromAddr"],
					]);
					$ticket_ID = $id;
					
			// ----- csatolások, képek kezelése ----- 
					$structure = imap_fetchstructure($imap, $uid, FT_UID);
//	var_dump($structure);		//	----- teszteléshez van
					if (property_exists($structure,"parts")) {
					$flattenedParts = $this->flattenParts($structure->parts);
					foreach ($flattenedParts as $index=> $part) {
						$decode = imap_fetchbody($imap, $uid, $index, FT_UID); //to get the base64 encoded string for the image
						if ($part->encoding){							
							$data = base64_decode($decode);
						}
						$filename = $this->getFilenameFromPart($part);
						if ($filename) {
							$utf8 = false;
							if ((substr($filename, 2, 5) == "UTF-8")){
								$utf8= true;
							}
							else {
								if ((substr($filename, 0, 2) <> "=?")) {
									$utf8= true;
								}
							}
							if (!$utf8) {
								$origName = mb_decode_mimeheader($filename);
							}
							else {
								$origName = $filename;
							}
						}	
						if (isset($data)) {
							$newFileName = (string)time().$uid.$index;
							switch ($part->subtype) {
								case 'JPEG': 
									$id = DB::table('uploads')->insertGetId(['filename' => $newFileName.'.jpg',
                                                    'origname' => $origName,
                                                    'mime'=> $part->subtype,
                                                    'type'=> 'attachment',
                                                    'connected_ticket'=> $ticket_ID]);
									file_put_contents('uploads/'.$newFileName.'.jpg' , $data);								
									break;
								case 'PNG': 
									$id = DB::table('uploads')->insertGetId(['filename' => $newFileName.'.png',
                                                    'origname' => $origName,
                                                    'mime'=> $part->subtype,
                                                    'type'=> 'attachment',
                                                    'connected_ticket'=> $ticket_ID]);
									file_put_contents('uploads/'.$newFileName.'.png' , $data);								
									break;
								case 'TIFF':
									$id = DB::table('uploads')->insertGetId(['filename' => $newFileName.'.tif',
                                                    'origname' => $origName,
                                                    'mime'=> $part->subtype,
                                                    'type'=> 'attachment',
                                                    'connected_ticket'=> $ticket_ID]);
									file_put_contents('uploads/'.$newFileName.'.tif' , $data);								
									break;							
								case 'PDF':
									$id = DB::table('uploads')->insertGetId(['filename' => $newFileName.'.pdf',
                                                    'origname' => $origName,
                                                    'mime'=> $part->subtype,
                                                    'type'=> 'attachment',
                                                    'connected_ticket'=> $ticket_ID]);
									file_put_contents('uploads/'.$newFileName.'.pdf' , $data);								
									break;
								case 'VND.OPENXMLFORMATS-OFFICEDOCUMENT.SPREADSHEETML.SHEET':
									$id = DB::table('uploads')->insertGetId(['filename' => $newFileName.'.xlsx',
                                                    'origname' => $origName,
                                                    'mime'=> $part->subtype,
                                                    'type'=> 'attachment',
                                                    'connected_ticket'=> $ticket_ID]);
									file_put_contents('uploads/'.$newFileName.'.xlsx' , $data);								
									break;
								case 'VND.MS-EXCEL':
									$id = DB::table('uploads')->insertGetId(['filename' => $newFileName.'.xls',
                                                    'origname' => $origName,
                                                    'mime'=> $part->subtype,
                                                    'type'=> 'attachment',
                                                    'connected_ticket'=> $ticket_ID]);
									file_put_contents('uploads/'.$newFileName.'.xls' , $data);								
									break;
								case 'VND.OPENXMLFORMATS-OFFICEDOCUMENT.WORDPROCESSINGML.DOCUMENT':
									$id = DB::table('uploads')->insertGetId(['filename' => $newFileName.'.docx',
                                                    'origname' => $origName,
                                                    'mime'=> $part->subtype,
                                                    'type'=> 'attachment',
                                                    'connected_ticket'=> $ticket_ID]);
									file_put_contents('uploads/'.$newFileName.'.docx' , $data);								
									break;
								case 'MSWORD':
									$id = DB::table('uploads')->insertGetId(['filename' => $newFileName.'.doc',
                                                    'origname' => $origName,
                                                    'mime'=> $part->subtype,
                                                    'type'=> 'attachment',
                                                    'connected_ticket'=> $ticket_ID]);
									file_put_contents('uploads/'.$newFileName.'.doc' , $data);								
									break;
								case ('ZIP' == $part->subtype or $part->subtype == 'X-ZIP-COMPRESSED'):
									$id = DB::table('uploads')->insertGetId(['filename' => $newFileName.'.zip',
                                                    'origname' => $origName,
                                                    'mime'=> $part->subtype,
                                                    'type'=> 'attachment',
                                                    'connected_ticket'=> $ticket_ID]);
									file_put_contents('uploads/'.$newFileName.'.zip' , $data);								
									break;
								case ('OCTET-STREAM'):
									$id = DB::table('uploads')->insertGetId(['filename' => $newFileName.'.7z',
                                                    'origname' => $origName,
                                                    'mime'=> $part->subtype,
                                                    'type'=> 'attachment',
                                                    'connected_ticket'=> $ticket_ID]);
									file_put_contents('uploads/'.$newFileName.'.7z' , $data);								
									break;
							}
						}
					}
			// ------ Logolás: új ticket------
					$partnername = DB::table('partners')->select('partner_name')->where('partner_ID',$partner->partner_ID)->first();
					$IP = "e-mail";
					$msg = "Új hibajegy";
					$id1 = DB::table('logs')->insertGetId([
						   'user_ID' => 1,
						   'ticket_ID' => $id,
						   'partner_ID' => $partner->partner_ID,
						   'action' => $msg,
						   'IP' => $IP]);

			// ------ E-mail küldés a felelősnek -------   Ez levelezésből lett megoldva!!!
/*					$subject = "Új hibajegy - #".$ticket_ID . " ". $partnername->partner_name;
					foreach ($owners as $owner) {                 
					    $owners_email = DB::table('users')->select('email')->where('id', $owner->user_ID)->first();
					    if (isset($owners_email))
						Mail::queue('emails.newTicketMail', ['title' => '#'.$ticket_ID . ' számú hibajegy rögzítésre került', 'content' => $details["subject"]], function ($message) use ($owners_email,$subject) {
								$message->to($owners_email->email);
								$message->subject($subject);
						});
					}
*/
			// ------ E-mail a bejelentés nyugtázására ------
					$toAddress = $details["fromAddr"];
                    $subject = "Értesítés  #".$ticket_ID." sorszámú hibajegy regisztrálásáról - IT Szerviz HelpDesk";
                    $content = "Köszönettel vettük bejelentését, melyet a #".$ticket_ID." sorszámú hibajegyen / feladatban regisztráltunk.<br>A bejelentés tárgya: ".$details["subject"].
                                "<br/>Kollegánk hamarosan felveszi Önnel a kapcsolatot emailben, vagy telefonon. Addig szíves türelmét és megértését kérjük.
                                <br/><br/>Üdvözlettel:<br/>IT Szerviz HelpDesk";
                    Mail::queue('emails.newTicketMail', ['title' => "Tisztelt Ügyfelünk!", 'content' => $content], function ($message) use ($toAddress,$subject) {
							$message->to($toAddress);
							$message->subject($subject);
					});				
					}
				}
		// -------  nem azonosított ügyfél kezelése  -------
				else {						
					$fw_message = imap_fetchheader($imap, $i).imap_body($imap, $i);
					$deststream = imap_open("{mail.negypolus.hu:143/imap}", "j@negypolus.hu", "Megane");
					$imapresult = imap_append($deststream,'{mail.negypolus.hu:143/imap}',$fw_message);
					if (!$imapresult) {
						error_log(imap_last_error()."\n", 3, "ticket.log");
					}
					imap_errors();
					imap_alerts();
					imap_close($deststream);
				}
			}
			}
	// ------ Feldolgozott emailek átmozgatása ---------
    		if ($numMessages > 0) {
    			$imapresult=imap_mail_move($imap,'1:'.$numMessages,'Saved');
    			if ($imapresult) {
    				error_log(imap_last_error()."\n", 3, "ticket.log");
    			}
    			$imapresult = imap_expunge($imap);			
    			if ($imapresult) {
    				error_log(imap_last_error()."\n", 3, "ticket.log");
    			}
    		}
		}
		
		imap_errors();
 		imap_alerts();
		imap_close($imap);
//		var_dump( $folders);
	}
    
    public function checkOwner() {
		$tickets = DB::table('tickets')->select('ticket_ID','created','ticket_state')->where('owner', 1)->get();
        foreach ($tickets as $ticket) {
            if ($ticket->ticket_state == "Új feladat")
            echo $ticket->ticket_ID." ".$ticket->created."<br>";
        }
        
    }
}
