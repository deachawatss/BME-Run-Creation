Imports System.ComponentModel
Imports System.IO.Ports
Imports System.Timers
Imports Newtonsoft.Json
Imports Newtonsoft.Json.Linq
Public Class frmPreweighFetchWt
    Private WithEvents myTimer As Timer
    Public strx As String
    Public reqwt As Double
    Public wtfr As Double
    Public wtto As Double
    Public strlotno As String
    Public myitemkey As String
    Public Dateexpiry As String

    Public mydict As New Dictionary(Of String, String)

    Public is_connected As Boolean
    Public serialPort As New SerialPort()
    Public myscale As String
    Public StdQtyDispUom As Double = 0
    Dim api As New ApiClass()
    Dim allergen As String

    Dim data_packsize As New Dictionary(Of String, JObject)

    Private myreg As Dictionary(Of String, Object) = ReadXmlFileToDictionary()
    Private Declare Function SendMessage Lib "user32" Alias "SendMessageA" (ByVal hWnd As IntPtr, ByVal wMsg As Integer, ByVal wParam As Integer, ByVal lParam As Integer) As Integer

    Private Sub frmPreweighFetchWt_Load(sender As Object, e As EventArgs) Handles Me.Load
        data_packsize = New Dictionary(Of String, JObject)()
        Dim postData As New Dictionary(Of String, String) From {
          {"prodcode", mydict("prodcode")},
          {"batchno", mydict("batchno")}
        }
        Dim myrequest = api.post_request("Partialwt/infofetchwt", postData)
        Debug.Write(myrequest)
        Dim jsonObject As JObject = DirectCast(myrequest, JObject)

        If CInt(jsonObject("msgno")) = 108 Then
            MsgBox("No Itemkey / Lot Found", MsgBoxStyle.Critical)
            Me.Close()
        End If

        Dim vtotalwt As Double = 0
        If jsonObject.ContainsKey("PickedList") Then
            Dim dta As JArray = jsonObject("PickedList")

            For Each item As JToken In dta
                DataGridView1.Rows.Add({
                                        "",
                                        item("itemkey"),
                                        item("lotno"),
                                        "",
                                        item("qty"),
                                        item("batchno"),
                                        item("runno"),
                                        "N",
                                        item("partialid")
                                       })

                vtotalwt = vtotalwt + CDbl(item("qty"))
            Next

            cmbbulksize.Items.Clear()

            Dim vpacksize = jsonObject("packsize")

            For Each pack_item As JProperty In vpacksize
                'Debug.WriteLine(pack_item.Value)
                cmbbulksize.Items.Add(pack_item.Value("FeatureValue"))
                data_packsize.Add(pack_item.Value("FeatureValue"), pack_item.Value)
            Next

            Dim tobepicked As JObject = jsonObject("ToBePicked")
            allergen = tobepicked.GetValue("allergen")
            If CInt(jsonObject("bulkpacksize").ToString) = 0 Then
                cmbbulksize.Enabled = False
                txtreqwt.Text = tobepicked.GetValue("req_qty")
                txtwtfr.Text = tobepicked.GetValue("wtfrom")
                txtwtto.Text = tobepicked.GetValue("wtto")

            Else
                cmbbulksize.Enabled = True
                cmbbulksize.SelectedIndex = 0
                'setRange()
            End If

        End If


        txtactualwt.Text = 0
        txttotalwt.Text = vtotalwt
        txtItemBarcode.Text = mydict("prodcode")
        txtRunNo.Text = mydict("RunNo")
        txtBatchNo.Text = mydict("batchno")
        txtstockonhand.Text = jsonObject("soh")
        txtactualwt.Focus()

        spr.Close()
        Dim scale_interval As Object
        If myscale = "SCALE 1" Then
            scale_interval = myreg("scale1_interval")
            spr.connect("scale1", txtactualwt)

        Else
            scale_interval = myreg("scale2_interval")
            spr.connect("scale2", txtactualwt)
        End If
    End Sub
    Sub setRange()
        Dim vpacksize = data_packsize(cmbbulksize.Text)
        'Debug.WriteLine(vpacksize)
        txtreqwt.Text = vpacksize.GetValue("mPartial")
        txtwtfr.Text = vpacksize.GetValue("wtfrom")
        txtwtto.Text = vpacksize.GetValue("wtto")
        txtactualwt.Focus()
    End Sub

    Private Sub cmbbulksize_SelectedIndexChanged(sender As Object, e As EventArgs) Handles cmbbulksize.SelectedIndexChanged
        setRange()
    End Sub

    Private Sub DataGridView1_RowsAdded(sender As Object, e As DataGridViewRowsAddedEventArgs) Handles DataGridView1.RowsAdded
        If DataGridView1.RowCount > 0 Then
            cmbbulksize.Enabled = False
        End If
    End Sub

    Private Sub txttotalwt_TextChanged(sender As Object, e As EventArgs) Handles txttotalwt.TextChanged
        Try
            Dim count As Double
            Dim pcount As Double

            If Double.TryParse(txtreqwt.Text, reqwt) Then
                reqwt = CDbl(txtreqwt.Text)
            Else
                reqwt = 0
            End If

            'ProgressBar1.Maximum = reqwt
            lblactwt.Text = txttotalwt.Text

            count = If(txttotalwt.Text = "", 0, CDbl(txttotalwt.Text))
            pcount = (count / reqwt) * 100



            If Double.IsNaN(pcount) Then
                ProgressBar1.Value = 0
                lblactwt.Text = 0
            ElseIf pcount >= 100 Then
                ProgressBar1.Value = 100
            ElseIf Double.IsInfinity(pcount) Then
                ProgressBar1.Value = 0
                lblactwt.Text = 0
            ElseIf pcount <= 0 Then
                ProgressBar1.Value = 0
                lblactwt.Text = 0
            Else
                ProgressBar1.Value = pcount
            End If


            With ProgressBar1
                If count < CDbl(txtwtfr.Text) Then SendMessage(.Handle, 1040, 3, 0)
                If count >= CDbl(txtwtfr.Text) And count <= txtwtto.Text Then SendMessage(.Handle, 1040, 1, 0)
                If count > CDbl(txtwtto.Text) Then SendMessage(.Handle, 1040, 2, 0)
            End With
            ProgressBar1.PerformStep()
        Catch ex As Exception

        End Try



    End Sub

    Private Sub txtactualwt_TextChanged(sender As Object, e As EventArgs) Handles txtactualwt.TextChanged
        changetotal()
        Timer2.Stop()
        Timer2.Start()
    End Sub

    Public Sub changetotal()
        Dim mysum As New Double
        Dim mytotal As New Double
        Dim mydt As New Double
        mysum = sumdata()

        'If Double.TryParse(txttotalwt.Text, mytotal) Then
        ' mytotal = CDbl(txttotalwt.Text)
        ' Else
        ' mytotal = 0
        'End If

        If Double.TryParse(txtactualwt.Text, mydt) Then
            mydt = CDbl(txtactualwt.Text)
        Else
            mydt = 0
        End If

        txttotalwt.Text = (mytotal + mysum + mydt).ToString("0.000000")
    End Sub

    Private Function sumdata() As Double
        Dim sum As Double = 0

        'If DataGridView1.RowCount > 0 Then
        'For Each cell As DataGridViewCell In DataGridView1.Rows(4).Cells
        'If cell.Value IsNot Nothing AndAlso IsNumeric(cell.Value) Then
        'sum += CDbl(cell.Value)
        'End If
        'Next
        'End If

        For Each row As DataGridViewRow In DataGridView1.Rows
            ' Do something with each row, for example:
            Dim value As Double
            If Double.TryParse(row.Cells("Qty").Value.ToString(), value) Then
                ' The cell value can be parsed to a double, do something with it
                ' For example, add it to a running total
                sum += value
            Else
                ' The cell value cannot be parsed to a double, handle the error
                MessageBox.Show("Error: invalid cell value")
            End If
        Next


        Return sum
    End Function

    Private Sub Button5_Click(sender As Object, e As EventArgs) Handles Button5.Click
        Timer2.Stop()
        Dim now As DateTime = DateTime.Now
        Dim mydate As String = now.ToString("MM/dd/yyyy HH:mm")
        Dim myuserinfo = UserInfo.getUserinfo()
        Dim sum As Double = 0
        Dim value As Double

        Dim x_txttotalwt = Math.Round(CDbl(txttotalwt.Text), 6, MidpointRounding.AwayFromZero)
        Dim x_txtwtfr = Math.Round(CDbl(txtwtfr.Text), 6, MidpointRounding.AwayFromZero)
        Dim x_txtwtto = Math.Round(CDbl(txtwtto.Text), 6, MidpointRounding.AwayFromZero)
        Dim x_txtreqwt = Math.Round(CDbl(txtreqwt.Text), 6, MidpointRounding.AwayFromZero)
        Dim toupload As JObject = New JObject()
        Dim data As JArray = New JArray()

        Try
            If (txtreqwt.Text <> "" And txtreqwt.Text <> 0) And (txtItemBarcode.Text <> "") And (txtBatchNo.Text <> "") And (txtRunNo.Text <> "") Then

                If txtactualwt.Text <> "" And txtactualwt.Text <> "0" Then

                    If CDbl(txtstockonhand.Text) >= CDbl(txtactualwt.Text) Then
                        If CDbl(txttotalwt.Text) <= CDbl(txtwtto.Text) Then
                            'Get actual  wt and convert to kgs

                            'DataGridView1.Rows.Add("", mydict("itemkey"), mydict("lotno"), "", txtactualwt.Text, txtBatchNo.Text, txtRunNo.Text, "N", "")
                            'txtactualwt.Text = 0

                            Dim postData As New Dictionary(Of String, String) From {
                              {"data", JsonConvert.SerializeObject(data)},
                              {"batchno", mydict("batchno")},
                              {"prodcode", mydict("prodcode")},
                              {"runno", mydict("RunNo")},
                              {"qty", txtactualwt.Text},
                              {"totalqty", x_txttotalwt},
                              {"wtfrom", x_txtwtfr},
                              {"wtto", x_txtwtto},
                              {"wtreq", x_txtreqwt},
                              {"uname", UserInfo.getUserinfo("uname")},
                              {"binno", mydict("BinNo")}
                            }
                            Dim myrequest = api.post_request("Partialwt/allocatewt", postData)
                            'Debug.Write(myrequest)
                            Dim jsonObject As JObject = DirectCast(myrequest, JObject)
                            MsgBox("Successfully Allocated", MsgBoxStyle.Information)
                            Me.Close()
                        Else
                            MsgBox("Total weight is greater than required weight", MsgBoxStyle.Critical)
                        End If
                    Else
                        MsgBox("Total weight is greater than stock on hand", MsgBoxStyle.Critical)

                    End If


                End If


            Else
                MsgBox("Invalid Input", MsgBoxStyle.Critical)

            End If

        Catch ex As Exception
            LogError(ex)

        End Try




        'For Each row As DataGridViewRow In DataGridView1.Rows
        '    If Double.TryParse(row.Cells("Qty").Value.ToString(), value) Then
        '        If row.Cells("statflag").Value.ToString = "N" Then
        '            Dim itemkey = row.Cells("ItemKey").Value.ToString()
        '            Dim batchno = row.Cells("batchno").Value.ToString()
        '            Dim lotno = row.Cells("LotNo").Value.ToString()
        '            Dim qty = row.Cells("Qty").Value.ToString()


        '            Dim data_upload As JObject = New JObject()

        '            data_upload("itemkey") = itemkey
        '            data_upload("batchno") = batchno
        '            data_upload("lotno") = lotno
        '            data_upload("qty") = qty

        '            data.Add(data_upload)

        '        End If
        '    End If
        'Next

        'If data.Count > 0 Then
        'toupload("data") = data


        ' End If

    End Sub

    Private Sub frmPreweighFetchWt_Closing(sender As Object, e As CancelEventArgs) Handles Me.Closing
        spr.Close()

        If frmPreweigh.searchby = "runno" Then
            'mydict.Add("batchno", txtBatchNo.Text)
            frmPreweigh.setmydata(mydict)

        Else
            ' frmPartialPick.setmydatabatch(mydict)
            frmPreweigh.setmydata(mydict)
        End If
        'frmPreweigh.dgindex = frmPreweigh.dgindex + 1
        txtBatchNo.Clear()
        txtRunNo.Clear()
        txtItemBarcode.Clear()
        txtreqwt.Text = 0
        txtwtfr.Text = 0
        txtwtto.Text = 0
        txtactualwt.Text = 0
        txttotalwt.Text = 0
        DataGridView1.Rows().Clear()
    End Sub

    Private Sub Button2_Click(sender As Object, e As EventArgs) Handles Button2.Click

        Timer2.Stop()
        Dim now As DateTime = DateTime.Now
        Dim mydate As String = now.ToString("MM/dd/yyyy HH:mm")
        Dim myuserinfo = UserInfo.getUserinfo()
        Dim sum As Double = 0
        Dim value As Double
        Dim nwf_printer As Object = myreg("nwf_printer")

        Dim x_txttotalwt = Math.Round(CDbl(txttotalwt.Text), 6, MidpointRounding.AwayFromZero)
        Dim x_txtwtfr = Math.Round(CDbl(txtwtfr.Text), 6, MidpointRounding.AwayFromZero)
        Dim x_txtwtto = Math.Round(CDbl(txtwtto.Text), 6, MidpointRounding.AwayFromZero)
        Dim x_txtreqwt = Math.Round(CDbl(txtreqwt.Text), 6, MidpointRounding.AwayFromZero)
        Dim toupload As JObject = New JObject()
        Dim data As JArray = New JArray()

        Try
            If (txtreqwt.Text <> "" And txtreqwt.Text <> 0) And (txtItemBarcode.Text <> "") And (txtBatchNo.Text <> "") And (txtRunNo.Text <> "") Then

                If txtactualwt.Text <> "" And txtactualwt.Text <> "0" Then

                    If CDbl(txtstockonhand.Text) >= CDbl(txtactualwt.Text) Then
                        If CDbl(txttotalwt.Text) <= CDbl(txtwtto.Text) Then
                            'Get actual  wt and convert to kgs

                            'DataGridView1.Rows.Add("", mydict("itemkey"), mydict("lotno"), "", txtactualwt.Text, txtBatchNo.Text, txtRunNo.Text, "N", "")
                            'txtactualwt.Text = 0

                            Dim postData As New Dictionary(Of String, String) From {
                                  {"data", JsonConvert.SerializeObject(data)},
                                  {"batchno", mydict("batchno")},
                                  {"prodcode", mydict("prodcode")},
                                  {"runno", mydict("RunNo")},
                                  {"qty", txtactualwt.Text},
                                  {"totalqty", x_txttotalwt},
                                  {"wtfrom", x_txtwtfr},
                                  {"wtto", x_txtwtto},
                                  {"wtreq", x_txtreqwt},
                                  {"uname", UserInfo.getUserinfo("uname")},
                                  {"binno", mydict("BinNo")}
                                }
                            Dim myrequest = api.post_request("Partialwt/allocatewt", postData)
                            'Debug.Write(myrequest)
                            Dim jsonObject As JObject = DirectCast(myrequest, JObject)

                            If jsonObject("msgno") = "200" Then
                                Dim myid = jsonObject("id").ToString()
                                Dim zbarcode = "nwf-pb-" & myid.PadLeft(11, "0")
                                Dim mystring As String

                                Dim filePath As String = "report\preweigh_wt.txt"
                                Dim fileContent As String = System.IO.File.ReadAllText(filePath)
                                Dim printdate As String = now.ToString("MM/dd/yyyy h:mm tt")

                                'Dim frm As New RptPreweighfrm
                                'frm.itemkey = mydict("itemkey")
                                'frm.lotno = mydict("lotno")
                                'frm.qty = txtactualwt.Text
                                'frm.batchno = mydict("batchno")

                                'frm.ShowDialog()

                                'mystring = String.Format("
                                '    ^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR4,4~SD15^JUS^LRN^CI0^XZ
                                '    ^XA
                                '    ^MMT
                                '    ^PW812
                                '    ^LL0203
                                '    ^LS0
                                '    ^FT788,164^A0I,39,36^FH\^FDItem key:^FS
                                '    ^FT788,120^A0I,39,36^FH\^FDLot No:^FS
                                '    ^FT788,79^A0I,39,36^FH\^FDQuantity:^FS
                                '    ^FT636,164^A0I,39,36^FH\^FD{0}^FS
                                '    ^FT636,79^A0I,39,36^FH\^FD{2}^FS
                                '    ^FT636,119^A0I,39,36^FH\^FD{1}^FS
                                '    ^BY2,3,44^FT554,27^BCI,,Y,N
                                '    ^FD{3}^FS
                                '    ^FT344,163^A0I,39,36^FH\^FDBatch No:^FS
                                '    ^FT193,163^A0I,39,36^FH\^FD{4}^FS
                                '    ^FT342,79^A0I,39,36^FH\^FDAllergen:^FS
                                '    ^FT206,79^A0I,39,36^FH\^FD{5}^FS
                                '    ^PQ1,0,1,Y^XZ

                                '", mydict("itemkey"), mydict("lotno"), txtactualwt.Text, zbarcode, mydict("batchno"), allergen)
                                '    BarcodePrinter.SendZplOverUsb(mystring, nwf_printer)

                                'mystring = String.Format("
                                '    ^XA
                                '    ^MMT
                                '    ^PW812
                                '    ^LL0812
                                '    ^LS0
                                '    ^FO224,0^BQN,2,20^FDQA,{4}*{5}^FS
                                '    ^FT494,692^A0I,113,112^FH\^FD{0}^FS
                                '    ^FT716,595^A0I,99,98^FH\^FD{1}^FS
                                '    ^FT296,595^A0I,99,98^FH\^FDKG^FS
                                '    ^FT482,458^A0I,99,98^FH\^FD{2}^FS
                                '    ^FT795,370^A0I,56,55^FH\^FD{3}^FS
                                '    ^FT468,370^A0I,56,55^FH\^FD09/26/2024^FS
                                '    ^FT201,370^A0I,56,55^FH\^FD6:12 PM^FS
                                '    ^PQ1,0,1,Y^XZ


                                '", mydict("itemkey"), txtactualwt.Text, mydict("batchno"), mydict("lotno"), mydict("itemkey"), txtactualwt.Text)

                                Dim templateFormat = fileContent.Replace("{itemkey}", mydict("itemkey"))
                                templateFormat = templateFormat.Replace("{qty}", txtactualwt.Text)
                                templateFormat = templateFormat.Replace("{batchno}", mydict("batchno"))
                                templateFormat = templateFormat.Replace("{lotno}", mydict("lotno"))
                                templateFormat = templateFormat.Replace("{printdate}", printdate)
                                templateFormat = templateFormat.Replace("{zbarcode}", zbarcode)
                                templateFormat = templateFormat.Replace("{allergen}", allergen)
                                templateFormat = templateFormat.Replace("{expiry}", Dateexpiry)
                                mystring = templateFormat

                                RawPrint(nwf_printer, mystring)


                            End If

                            MsgBox("Successfully Allocated", MsgBoxStyle.Information)
                            Me.Close()
                        Else
                            MsgBox("Total weight is greater than required weight", MsgBoxStyle.Critical)
                        End If
                    Else
                        MsgBox("Total weight is greater than stock on hand", MsgBoxStyle.Critical)

                    End If


                End If


            Else
                MsgBox("Invalid Input", MsgBoxStyle.Critical)

            End If

        Catch ex As Exception
            LogError(ex)

        End Try

    End Sub


    Private Sub Timer2_Tick(sender As Object, e As EventArgs) Handles Timer2.Tick
        Dim wt As New Double
        Dim autofetch = 0

        If myreg.TryGetValue("autofetch", autofetch) Then
            If autofetch = 1 Then
                Timer2.Stop()
                If Double.TryParse(txtactualwt.Text, wt) And CDbl(txtreqwt.Text) > 0 Then

                    If wt >= CDbl(txtwtfr.Text) And wt <= CDbl(txtwtto.Text) Then
                        'MsgBox("time")
                        Button2.PerformClick()

                    End If

                End If
            End If
        End If

    End Sub

End Class