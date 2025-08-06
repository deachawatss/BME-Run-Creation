<?php
require('fpdf.php');



class PDF extends FPDF
{
	var $widths;
	var $aligns;
	var $sign1_p;
	var $sign1;
	var $sign2_p;
	var $sign2;
	
	
	function setFooter($x=array(),$y=array()){
		$this->sign1=$x[0];
		$this->sign1_p=$y[0];
		$this->sign2=$x[1];
		$this->sign2_p=$y[1];
	}
	
	function SetWidths($w)
	{
		//Set the array of column widths
		$this->widths=$w;
	}

	function SetAligns($a)
	{
		//Set the array of column alignments
		$this->aligns=$a;
	}
	
	function Row($data)
	{
		//Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=5*$nb;
		//Issue a page break first if needed
		$this->CheckPageBreak($h);
		//Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
			//Draw the border
			$this->Rect($x,$y,$w,$h);
			//Print the text
			$this->MultiCell($w,5,$data[$i],0,$a);
			//Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
		//Go to the next line
		$this->Ln($h);
	}
	
	function Row_blank($data)
	{
		//Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=5*$nb;
		//Issue a page break first if needed
		$this->CheckPageBreak($h);
		//Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
			//Draw the border
			#$this->Rect($x,$y,$w,$h);
			//Print the text
			$this->MultiCell($w,5,$data[$i],0,$a);
			//Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
		//Go to the next line
		$this->Ln($h);
	}

	function CheckPageBreak($h)
	{
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>$this->PageBreakTrigger)
			$this->AddPage($this->CurOrientation);
	}

	function NbLines($w,$txt)
	{
		//Computes the number of lines a MultiCell of width w will take
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")
			$nb--;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb)
		{
			$c=$s[$i];
			if($c=="\n")
			{
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				continue;
			}
			if($c==' ')
				$sep=$i;
			$l+=$cw[$c];
			if($l>$wmax)
			{
				if($sep==-1)
				{
					if($i==$j)
						$i++;
				}
				else
					$i=$sep+1;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
			}
			else
				$i++;
		}
		return $nl;
	}
	// Page header
	function Header($time="")
	{
		// Logo
		/*$this->Image('http://192.168.1.38/hris/assets/img/Logo.jpg',10,6,30);
		
		$this->SetFont('Arial','',8);
		$this->SetXY(40,9);
		$this->Cell(0, 0, "Newly Weds Foods (Philippines)", 0, 0, 'L');
		
		$this->SetFont('Arial','',8);
		$this->SetXY(40,13);
		$this->Cell(0, 0, "Unit A, 103 Excellence Avenue,", 0, 0, 'L');
		
		$this->SetFont('Arial','',8);
		$this->SetXY(40,17);
		$this->Cell(0, 0, "Carmelray Industrial Park I ", 0, 0, 'L');
		
		$this->SetFont('Arial','',8);
		$this->SetXY(40,21);
		$this->Cell(0, 0, "Canlubang, Calamba, Laguna 4028 ", 0, 0, 'L');
		
		$this->SetFont('Arial','B',12);
		$this->SetXY(220,9);
		$this->Cell(0, 0, "TIME RECORD", 0, 0, 'L');
		
		$this->SetFont('Arial','',8);
		$this->SetXY(220,15);
		$this->Cell(30, 0, date("F d - ",strtotime($_GET['from']))."".date("F d, Y",strtotime($_GET['to'])), 0, 0, 'C');
		$this->Ln(15);*/
	}

	// Page footer
	function Footer()
	{
		 /* if(@$_SESSION['userinfo'][0]->empno=="19080102"){
          //$this->fpdf->footer();
		  
		    $this->SetAligns(array('C','C','C','C'));
            $this->SetWidths(array(126,140));
            $this->Row_blank( array($this->sign1, $this->sign2  ) );
            $this->Row_blank( array($this->sign1_p,$this->sign2_p) );
		  
        }  */
		
		// Position at 1.5 cm from bottom
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// Page number
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		
	}

	function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $maxline=0)
    {
        //Output text with automatic or explicit line breaks, at most $maxline lines
        $cw=&$this->CurrentFont['cw'];
        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r",'',$txt);
        $nb=strlen($s);
        if($nb>0 && $s[$nb-1]=="\n")
            $nb--;
        $b=0;
        if($border)
        {
            if($border==1)
            {
                $border='LTRB';
                $b='LRT';
                $b2='LR';
            }
            else
            {
                $b2='';
                if(is_int(strpos($border,'L')))
                    $b2.='L';
                if(is_int(strpos($border,'R')))
                    $b2.='R';
                $b=is_int(strpos($border,'T')) ? $b2.'T' : $b2;
            }
        }
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $ns=0;
        $nl=1;
        while($i<$nb)
        {
            //Get next character
            $c=$s[$i];
            if($c=="\n")
            {
                //Explicit line break
                if($this->ws>0)
                {
                    $this->ws=0;
                    $this->_out('0 Tw');
                }
                $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $ns=0;
                $nl++;
                if($border && $nl==2)
                    $b=$b2;
                if($maxline && $nl>$maxline)
                    return substr($s,$i);
					continue;
            }
            if($c==' ')
            {
                $sep=$i;
                $ls=$l;
                $ns++;
            }
            $l+=$cw[$c];
            if($l>$wmax)
            {
                //Automatic line break
                if($sep==-1)
                {
                    if($i==$j)
                        $i++;
                    if($this->ws>0)
                    {
                        $this->ws=0;
                        $this->_out('0 Tw');
                    }
                    $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
                }
                else
                {
                    if($align=='J')
                    {
                        $this->ws=($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
                        $this->_out(sprintf('%.3F Tw',$this->ws*$this->k));
                    }
                    $this->Cell($w,$h,substr($s,$j,$sep-$j),$b,2,$align,$fill);
                    $i=$sep+1;
                }
                $sep=-1;
                $j=$i;
                $l=0;
                $ns=0;
                $nl++;
                if($border && $nl==2)
                    $b=$b2;
                if($maxline && $nl>$maxline)
                {
                    if($this->ws>0)
                    {
                        $this->ws=0;
                        $this->_out('0 Tw');
                    }
                    return substr($s,$i);
                }
            }
            else
                $i++;
        }
        //Last chunk
        if($this->ws>0)
        {
            $this->ws=0;
            $this->_out('0 Tw');
        }
        if($border && is_int(strpos($border,'B')))
            $b.='B';
        $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
        $this->x=$this->lMargin;
        return '';
    }
	
	function WordWrap($text, $maxwidth)
	{
		
		$text = trim($text);
		if ($text=='')
			return 1;
		$space = $this->GetStringWidth(' ');
		$lines = explode("\n", $text);
		$text = '';
		$count = 0;

		foreach ($lines as $line)
		{
			$words = preg_split('/ +/', $line);
			$width = 0;

			foreach ($words as $word)
			{
				$wordwidth = $this->GetStringWidth($word);
				if ($wordwidth > $maxwidth)
				{
					// Word is too long, we cut it
					for($i=0; $i<strlen($word); $i++)
					{
						$wordwidth = $this->GetStringWidth(substr($word, $i, 1));
						if($width + $wordwidth <= $maxwidth)
						{
							$width += $wordwidth;
							$text .= substr($word, $i, 1);
						}
						else
						{
							$width = $wordwidth;
							$text = rtrim($text)."\n".substr($word, $i, 1);
							$count++;
						}
					}
				}
				elseif($width + $wordwidth <= $maxwidth)
				{
					$width += $wordwidth + $space;
					$text .= $word.' ';
				}
				else
				{
					$width = $wordwidth + $space;
					$text = rtrim($text)."\n".$word.' ';
					$count++;
				}
			}
			$text = rtrim($text)."\n";
			$count++;
		}
		$text = rtrim($text);
		return $count;
	}

	function Circle($x, $y, $r, $style='D')
	{
		$this->Ellipse($x,$y,$r,$r,$style);
	}

	function Ellipse($x, $y, $rx, $ry, $style='D')
	{
		if($style=='F')
			$op='f';
		elseif($style=='FD' || $style=='DF')
			$op='B';
		else
			$op='S';
		$lx=4/3*(M_SQRT2-1)*$rx;
		$ly=4/3*(M_SQRT2-1)*$ry;
		$k=$this->k;
		$h=$this->h;
		$this->_out(sprintf('%.2F %.2F m %.2F %.2F %.2F %.2F %.2F %.2F c',
			($x+$rx)*$k,($h-$y)*$k,
			($x+$rx)*$k,($h-($y-$ly))*$k,
			($x+$lx)*$k,($h-($y-$ry))*$k,
			$x*$k,($h-($y-$ry))*$k));
		$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
			($x-$lx)*$k,($h-($y-$ry))*$k,
			($x-$rx)*$k,($h-($y-$ly))*$k,
			($x-$rx)*$k,($h-$y)*$k));
		$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
			($x-$rx)*$k,($h-($y+$ly))*$k,
			($x-$lx)*$k,($h-($y+$ry))*$k,
			$x*$k,($h-($y+$ry))*$k));
		$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c %s',
			($x+$lx)*$k,($h-($y+$ry))*$k,
			($x+$rx)*$k,($h-($y+$ly))*$k,
			($x+$rx)*$k,($h-$y)*$k,
			$op));
	}

}