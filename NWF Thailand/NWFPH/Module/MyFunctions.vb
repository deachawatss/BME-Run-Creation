Imports System.IO
Imports Newtonsoft.Json.Linq
Imports System.Data
Imports ZXing
Imports System.Drawing
Module MyFunctions
    Dim myreg As Dictionary(Of String, Object) = ReadXmlFileToDictionary()
    Public spr As New SerialPortReceiver

    Public Function mybarcode(ByVal str As String) As Dictionary(Of String, String)
        Dim barcodeParts As New Dictionary(Of String, String)
        barcodeParts.Add("barcode", str)
        Dim pp As String() = str.Split("("c)
        Dim ppx As String()
        For Each val As String In pp
            If val.Length > 0 Then
                ppx = val.Split(")"c)
                If ppx.Length >= 2 Then
                    Select Case ppx(0)
                        Case "02"
                            barcodeParts("02") = ppx(1)
                        Case "10"
                            barcodeParts("10") = ppx(1)
                        Case "17"
                            barcodeParts("17") = ppx(1)
                    End Select
                End If
            End If
        Next
        Return barcodeParts
    End Function

    Public Function getmydata(ByVal mydata As Dictionary(Of String, String)) As Dictionary(Of String, String)
        Dim bmedb As New MsSQL()
        Dim nwfdb As New MySQL()

        ' access the data in the cells of the row
        Dim RunNo As Integer = mydata("RunNo")
        'Dim FormulaID As String = mydata("FormulaID")
        Dim BatchNo As String = mydata("BatchNo")
        Dim ItemKey As String = mydata("ItemKey")
        Dim lotno As String = mydata("LotNo")
        Dim myitem As New Dictionary(Of String, String)
        Dim totalneeded As Double = 0
        Dim pickedqty As Double = 0
        Dim pickedbulk As Double = 0
        Dim pickedpartial As Double = 0
        Dim minbulk As Double = 0
        Dim myPartial As Double = 0
        Dim pbalance As Double = 0

        Dim topicksql_rawmat = String.Format("
                        SELECT 
                            DISTINCT
	                            ItemKey,
	                            FormulaId,
	                            Desc1,
	                            StdQtyDispUom,
	                            featurevalue,
	                            PartialData,
	                            ""Bulk"",
	                            BatchNo,
	                            User2,
	                            wtfrom,
	                            wtto,
	                            BatchTicketDate
                        from v_prod_assembly where User2 = '{0}' AND LineTyp = 'FI'  and BatchNo = '{1}' and ItemKey = '{2}'
                ", RunNo, BatchNo, ItemKey)
        Dim topickrawmat = bmedb.SelectDataScalar(topicksql_rawmat)
        'myPartial = topickrawmat.Item("PartialData")


        'Debug.WriteLine(topickrawmat)
        If topickrawmat.Count > 0 Then

            If Double.TryParse(topickrawmat.Item("PartialData").ToString, myPartial) Then
                myPartial = CDbl(topickrawmat.Item("PartialData"))

            Else
                myPartial = CDbl(topickrawmat.Item("StdQtyDispUom"))
            End If

            'Picked Summary
            Dim pickedsql = String.Format("
                           Select 
                                runno,
                                itemkey,
                                sum(qty) as qty, 
                                sum(qtybulk) as qtybulk, 
                                sum(qtypartial) as qtypartial, 
                                GROUP_CONCAT(lotno) as lotno ,
                                batchno
                                    from v_allpicked 
                                where 
                                runno = '{0}' and batchno = '{1}' and itemkey='{2}' group by itemkey,batchno
                    ", RunNo, BatchNo, ItemKey)

            Dim pickeddata = nwfdb.SelectDataScalar(pickedsql)

            If Double.TryParse(topickrawmat.Item("StdQtyDispUom"), totalneeded) Then
                totalneeded = CDbl(topickrawmat.Item("StdQtyDispUom").ToString)
            End If
            If Double.TryParse(topickrawmat.Item("featurevalue").ToString, minbulk) Then
                minbulk = CDbl(topickrawmat.Item("featurevalue").ToString)
            End If

            If pickeddata.Count > 0 Then

                'myrec.Item(
                If Double.TryParse(pickeddata.Item("qty").ToString, pickedqty) Then
                    pickedqty = CDbl(pickeddata.Item("qty").ToString)
                End If

                If Double.TryParse(pickeddata.Item("qtybulk").ToString, pickedbulk) Then
                    If pickeddata.Item("qtybulk") > 0 Then
                        pickedbulk = CDbl(pickeddata.Item("qtybulk").ToString)
                    Else
                        If Double.TryParse(pickeddata.Item("qtybulk").ToString, pickedbulk) Then
                            pickedbulk = CDbl(topickrawmat.Item("Bulk").ToString) * minbulk
                        Else
                            pickedbulk = 0
                        End If

                    End If

                End If

                If Double.TryParse(pickeddata.Item("qtypartial").ToString, pickedpartial) Then
                    pickedpartial = CDbl(pickeddata.Item("qtypartial").ToString)
                End If

            Else

                If Double.TryParse(topickrawmat.Item("Bulk").ToString, pickedbulk) Then
                    pickedbulk = CDbl(topickrawmat.Item("Bulk").ToString) * minbulk
                End If


            End If



            myPartial = totalneeded - pickedbulk
            pbalance = Math.Round(CDbl(myPartial) - CDbl(pickedpartial), 6)

            'query 
            Dim q1 = String.Format(" SELECT TOP 1
                        lm.lotno,
	                    lm.qtyonhand,
	                    lm.qtycommitsales,
	                    lm.Dateexpiry,
	                    lm.BinNo,
	                    (lm.qtyonhand - lm.Qtycommitsales) AS QtyAvailable
                    FROM
	                     lotmaster lm 
                    WHERE
                     lm.ItemKey = '{0}'
                    AND lm.lotno = '{1}'
                    AND lm.BinNo = 'A-PREWEIGH'
                    AND lm.locationkey = 'MAIN'
                    AND (lm.qtyonhand - lm.Qtycommitsales) > 0
                    
                    ", ItemKey, lotno)
            Dim q1d = bmedb.SelectDataScalar(q1)
            '  Debug.WriteLine(q1)
            If q1d.Count > 0 Then

                Dim mPartial = Math.Round(myPartial, 6)
                Dim wtfrom1p As Double = 0
                Dim wtfrom As Double = 0
                Dim wtto1p As Double = 0
                Dim wtto As Double = 0

                If Double.TryParse(topickrawmat.Item("wtfrom").ToString, wtfrom1p) Then
                    wtfrom1p = CDbl(topickrawmat.Item("wtfrom").ToString)
                Else
                    wtfrom1p = 0.00075
                End If


                If Double.TryParse(topickrawmat.Item("wtto").ToString, wtto1p) Then
                    wtto1p = CDbl(topickrawmat.Item("wtto").ToString)
                Else
                    wtto1p = 0.00075
                End If

                wtfrom = Math.Round((mPartial - (mPartial * wtfrom1p)), 6, MidpointRounding.AwayFromZero)
                wtto = Math.Round((mPartial + (mPartial * wtto1p)), 6, MidpointRounding.AwayFromZero)

                myitem.Add("lotno", NotNull(q1d.Item("lotno"), "").ToString)
                myitem.Add("qtyonhand", NotNull(q1d.Item("qtyonhand"), "").ToString)
                myitem.Add("qtycommitsales", NotNull(q1d.Item("qtycommitsales"), "").ToString)
                myitem.Add("Dateexpiry", NotNull(q1d.Item("Dateexpiry"), "".ToString))
                myitem.Add("QtyAvailable", NotNull(q1d.Item("QtyAvailable"), "").ToString)
                myitem.Add("ItemKey", NotNull(topickrawmat.Item("ItemKey"), "").ToString)
                myitem.Add("FormulaId", NotNull(topickrawmat.Item("FormulaId"), "").ToString)
                myitem.Add("Desc1", NotNull(topickrawmat.Item("Desc1"), "").ToString)
                myitem.Add("StdQtyDispUom", NotNull(topickrawmat.Item("StdQtyDispUom"), "").ToString)
                myitem.Add("featurevalue", NotNull(topickrawmat.Item("featurevalue"), "").ToString)
                myitem.Add("PartialData", NotNull(topickrawmat.Item("PartialData"), "").ToString)
                myitem.Add("Bulk", NotNull(topickrawmat.Item("Bulk"), "").ToString)
                myitem.Add("BatchNo", NotNull(topickrawmat.Item("BatchNo"), "").ToString)
                myitem.Add("User2", NotNull(topickrawmat.Item("User2"), "").ToString)
                myitem.Add("wtfrom", wtfrom)
                myitem.Add("wtto", wtto)
                myitem.Add("BatchTicketDate", NotNull(topickrawmat.Item("BatchTicketDate"), "").ToString)
                myitem.Add("myPartial", myPartial)
                myitem.Add("pbalance", pbalance)
                myitem.Add("qtybulk", pickedbulk)
                myitem.Add("qtypartial", pickedpartial)
                myitem.Add("tqty", pickedqty)
            End If

        End If


        Return myitem
    End Function

    Sub LogError(ByVal ex As Exception)

        Dim debugstatus = myreg("debugstatus")
        Dim logFilePath As String = "error.log"

        If myreg.TryGetValue("debugstatus", debugstatus) Then

            If debugstatus = 1 Then
                ' Create or open the log file for appending
                Using writer As StreamWriter = File.AppendText(logFilePath)
                    writer.WriteLine("Error Date/Time: " & DateTime.Now.ToString())
                    writer.WriteLine("Error Message: " & ex.Message)
                    'writer.WriteLine("Stack Trace: " & ex.StackTrace)
                    writer.WriteLine("--------------------------------------------------")
                End Using
            End If

        End If

    End Sub

    Sub DebugLog(ByVal ex As String)

        Dim debugstatus = myreg("debugstatus")
        Dim logFilePath As String = "error.log"

        Using writer As StreamWriter = File.AppendText(logFilePath)
            writer.WriteLine("Error Date/Time: " & DateTime.Now.ToString())
            writer.WriteLine("Error Message: " & ex)
            'writer.WriteLine("Stack Trace: " & ex.StackTrace)
            writer.WriteLine("--------------------------------------------------")
        End Using

        'If myreg.TryGetValue("debugstatus", debugstatus) Then

        '    If debugstatus = 1 Then
        '        ' Create or open the log file for appending
        '        Using writer As StreamWriter = File.AppendText(logFilePath)
        '            writer.WriteLine("Error Date/Time: " & DateTime.Now.ToString())
        '            writer.WriteLine("Error Message: " & ex.Message)
        '            'writer.WriteLine("Stack Trace: " & ex.StackTrace)
        '            writer.WriteLine("--------------------------------------------------")
        '        End Using
        '    End If

        'End If

    End Sub

    Public Function getSuggested(ByVal str As String) As Dictionary(Of String, Object)
        ' Dim mysuggested As New Dictionary(Of String, String)
        Dim bmedb As New MsSQL()

        'Suggested
        Dim suggestedsql = String.Format("
                           Select 
                                TOP 1
                                LM.itemkey,
                                LM.lotno,
                                LM.qtyonhand,
                                LM.qtycommitsales,
                                LM.Dateexpiry,
                                LM.BinNo,
                                (LM.qtyonhand - LM.Qtycommitsales) AS 'QtyAvailable',
                                LF.featurevalue
                                    from LotMaster LM 
                                    left join LotFeaturesValue LF on LF.itemkey = LM.itemkey AND LM.LotNo = LF.LotNo AND LF.featureid = 'BAGSIZE'
                                where 
                                (
                                LM.ItemKey = '{0}' and 
                                LM.locationkey = 'MAIN' and 
                                (LM.qtyonhand - LM.Qtycommitsales) > 0 
                                ) and 
                                (LM.BinNo = 'A-PREWEIGH')
                                
                                order by
                                LM.dateexpiry asc, LM.LotNo asc
                    ", str)
        'Debug.WriteLine(suggestedsql)
        Dim suggesteddata = bmedb.SelectDataScalar(suggestedsql)
        Return suggesteddata
    End Function

    Public Function chkifvalid() As Boolean

        Return False
    End Function

    Public Function JArrayToDataTable(ByVal jArray As JArray, ByVal tblname As String) As DataTable
        Dim dataTable As New DataTable(tblname)

        ' Check if JArray is not Nothing and contains elements
        If jArray IsNot Nothing AndAlso jArray.Count > 0 Then
            ' Create columns based on the properties of the first JObject in the JArray
            For Each prop As JProperty In jArray(0).ToObject(Of JObject).Properties()
                dataTable.Columns.Add(prop.Name, GetType(String)) ' Assuming all columns are of type String
            Next

            ' Populate DataTable with data from JArray
            For Each jObj As JObject In jArray
                Dim row As DataRow = dataTable.NewRow()

                For Each prop As JProperty In jObj.Properties()
                    ' Check if the column exists in the DataTable
                    If dataTable.Columns.Contains(prop.Name) Then
                        row(prop.Name) = prop.Value.ToString() ' Convert value to string
                    End If
                Next

                dataTable.Rows.Add(row)
            Next
        End If

        Return dataTable
    End Function

    Public Function numberFormat(num As String) As String
        Dim number As Double = Convert.ToDouble(num)
        Dim roundedNumber As Double = Math.Round(number, 2)
        Dim formattedNumber As String = roundedNumber.ToString("N2")
        Return formattedNumber
    End Function
    Public Function GenerateQRCode(content As String) As Bitmap
        Dim writer As New BarcodeWriter()
        writer.Format = BarcodeFormat.QR_CODE
        writer.Options = New ZXing.Common.EncodingOptions With {.Height = 300, .Width = 300}
        Return writer.Write(content)
    End Function

    Public Function ConvertImageToByteArray(image As Image) As Byte()
        Using ms As New MemoryStream()
            image.Save(ms, System.Drawing.Imaging.ImageFormat.Png)
            Return ms.ToArray()
        End Using
    End Function

    Public Function GenerateQRCodeToTempFile(content As String) As String
        ' Create a unique temporary file name with the .png extension
        Dim tempFilePath As String = Path.Combine(Path.GetTempPath(), Path.GetRandomFileName() & ".png")

        ' Generate the QR code
        Dim writer As New BarcodeWriter()
        writer.Format = BarcodeFormat.QR_CODE
        writer.Options = New ZXing.Common.EncodingOptions With {.Height = 200, .Width = 200}

        ' Generate and save the QR code to the temp file path
        Dim qrImage As Bitmap = writer.Write(content)
        qrImage.Save(tempFilePath, System.Drawing.Imaging.ImageFormat.Png)

        ' Return the path to the temp file
        Return tempFilePath
    End Function

End Module
