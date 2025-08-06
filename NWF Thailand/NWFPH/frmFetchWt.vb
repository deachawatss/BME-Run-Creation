Imports System.ComponentModel
Imports System.IO.Ports
Imports System.Timers
Imports System.Net.Sockets
Imports System.Text.Encoding
Imports System.Text.RegularExpressions

Public Class frmFetchWt
    Private WithEvents myTimer As Timer
    Public strx As String
    Public reqwt As Double
    Public wtfr As Double
    Public wtto As Double
    Public strlotno As String
    Public myitemkey As String
    Public Dateexpiry As String

    Public is_connected As Boolean
    Public serialPort As New SerialPort()
    Public myscale As String
    Public StdQtyDispUom As Double = 0
    Private myreg As Dictionary(Of String, Object) = ReadXmlFileToDictionary()
    'Private ssport As New SerialHelper

    Private Declare Function SendMessage Lib "user32" Alias "SendMessageA" (ByVal hWnd As IntPtr, ByVal wMsg As Integer, ByVal wParam As Integer, ByVal lParam As Integer) As Integer

    Private Sub loaddata()

    End Sub

    Private Sub frmFetchWt_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        ProgressBar1.BackColor = Color.Blue
        spr.Close()

        ' Set the progress indicator color to green
        ProgressBar1.ForeColor = Color.Green
        Dim scale_interval As Object
        'frmMainBeta.ssport.Close()
        If myscale = "SCALE 1" Then
            'frmMainBeta.ssport.connect("scale1")
            scale_interval = myreg("scale1_interval")
            spr.connect("scale1", txtactualwt)

        Else
            'frmMainBeta.ssport.connect("scale2")
            scale_interval = myreg("scale2_interval")
            spr.connect("scale2", txtactualwt)
        End If

        Try


            'Dim myinterval = CShort(scale_interval) * 1000

            ' myTimer = New Timer(myinterval) ' 1 second interval
            ' myTimer.Enabled = True

            Timer1.Interval = scale_interval
            Timer1.Start()
        Catch ex As Exception
            LogError(ex)
            'Debug.WriteLine(ex.Message)
        End Try

    End Sub

    Private Sub frmFetchWt_Closing(sender As Object, e As CancelEventArgs) Handles Me.Closing
        spr.Close()

        Dim mydict As New Dictionary(Of String, String)

        Dim RunNo As String = frmPartialPick.txtRunNo.Text
        Dim FormulaID As String = frmPartialPick.myformulaid
        Dim BatchSize As String = frmPartialPick.txtBagWt.Text
        Dim Batch As String = frmPartialPick.txtBatches.Text

        mydict.Add("RunNo", RunNo)
        mydict.Add("FormulaID", FormulaID)
        mydict.Add("BatchSize", BatchSize)
        mydict.Add("Batch", Batch)
        mydict.Add("batchno", txtBatchNo.Text)
        If frmPartialPick.searchby = "runno" Then
            'mydict.Add("batchno", txtBatchNo.Text)
            frmPartialPick.setmydata(mydict)
        Else
            frmPartialPick.setmydatabatch(mydict)
        End If

        'frmMainBeta.ssport.Close()

        txtBatchNo.Clear()
        txtRunNo.Clear()
        txtItemBarcode.Clear()
        txtreqwt.Text = 0
        txtwtfr.Text = 0
        txtwtto.Text = 0
        txtactualwt.Text = 0
        txttotalwt.Text = 0
        DataGridView1.Rows().Clear()
        strlotno = ""
        myitemkey = ""
        Dateexpiry = ""
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

    Private Sub Button2_Click(sender As Object, e As EventArgs) Handles btnRunno.Click
        txtBatchNo.Clear()
        txtRunNo.Clear()
        txtItemBarcode.Clear()
        strlotno = ""
        myitemkey = ""
        Dateexpiry = ""
        frmFetchWtRunModal.ShowDialog()

    End Sub

    Private Sub btnBatchno_Click(sender As Object, e As EventArgs) Handles btnBatchno.Click
        txtBatchNo.Clear()
        txtItemBarcode.Clear()
        strlotno = ""
        myitemkey = ""
        Dateexpiry = ""
        frmFetchWtBatchList.ShowDialog()
    End Sub

    Private Sub itemBarcode_Click(sender As Object, e As EventArgs) Handles itemBarcode.Click
        txtItemBarcode.Clear()
        strlotno = ""
        myitemkey = ""
        Dateexpiry = ""
        frmFetchWtItemList.ShowDialog()
    End Sub

    Private Sub Button1_Click()
        Try
            If (txtreqwt.Text <> "" And txtreqwt.Text <> 0) And (txtItemBarcode.Text <> "") And (txtBatchNo.Text <> "") And (txtRunNo.Text <> "") Then

                If txtactualwt.Text <> "" And txtactualwt.Text <> "0" Then

                    If CDbl(txtstockonhand.Text) >= CDbl(txtactualwt.Text) Then
                        If CDbl(txttotalwt.Text) <= CDbl(txtwtto.Text) Then
                            'Get actual  wt and convert to kgs

                            DataGridView1.Rows.Add("", myitemkey, strlotno, Dateexpiry, txtactualwt.Text, txtBatchNo.Text, txtRunNo.Text, "N", "")
                            txtactualwt.Text = 0
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

    Private Sub Button5_Click(sender As Object, e As EventArgs) Handles Button5.Click
        Dim sum As Double = 0
        Dim mdb As New MySQL()
        Dim msdb As New MsSQL()
        Dim value As Double
        Button1_Click()
        Timer2.Stop()
        Dim now As DateTime = DateTime.Now
        Dim mydate As String = now.ToString("MM/dd/yyyy HH:mm")
        Dim myuserinfo = UserInfo.getUserinfo()


        Dim x_txttotalwt = Math.Round( CDbl(txttotalwt.Text), 6, MidpointRounding.AwayFromZero)
        Dim x_txtwtfr =  Math.Round(CDbl(txtwtfr.Text) , 6, MidpointRounding.AwayFromZero)
        Dim x_txtwtto = Math.Round(CDbl(txtwtto.Text), 6, MidpointRounding.AwayFromZero)
        Dim x_txtreqwt = Math.Round(CDbl(txtreqwt.Text), 6, MidpointRounding.AwayFromZero)

        For Each row As DataGridViewRow In DataGridView1.Rows
            now.ToString("o")
            If Double.TryParse(row.Cells("Qty").Value.ToString(), value) Then
                If row.Cells("statflag").Value.ToString = "N" Then

                    Dim itemkey = row.Cells("ItemKey").Value.ToString()
                    Dim batchno = row.Cells("batchno").Value.ToString()
                    Dim lotno = row.Cells("LotNo").Value.ToString()
                    Dim qty = row.Cells("Qty").Value.ToString()

                    ' Do something with each row, for example:
                    Dim mdata As New Dictionary(Of String, String)
                    mdata.Add("runno", row.Cells("runno").Value.ToString())
                    mdata.Add("itemkey", row.Cells("ItemKey").Value.ToString())
                    mdata.Add("lotno", row.Cells("LotNo").Value.ToString())
                    mdata.Add("binno", "A-PREWEIGH") 'row.Cells("Qty").Value.ToString()
                    mdata.Add("qty", row.Cells("Qty").Value.ToString())
                    mdata.Add("batchno", row.Cells("batchno").Value.ToString())
                    mdata.Add("required_qty", x_txtreqwt)
                    mdb.Create("tbl_rm_allocate_partial", mdata)

                    Dim pnitem As New Dictionary(Of String, Object)
                    Dim pnitem_qry = String.Format("Select * from PNITEM where ItemKey = '{0}' and BatchNo = '{1}' and LineTyp= 'FI'", itemkey, batchno)
                    pnitem = msdb.SelectDataScalar(pnitem_qry)

                    Dim lotdetails As Dictionary(Of String, Object)
                    Dim lotdetails_qry = String.Format("
                        Select LotMaster.*,INLOC.inclasskey , Convert(date,LotMaster.DateExpiry) as DateExpiry1
                            from LotMaster
                        left join INLOC on INLOC.itemkey = LotMaster.Itemkey and INLOC.Location = LotMaster.LocationKey
                        where 
                           LotMaster.LotNo = '{0}' and 
                           LotMaster.BinNo = 'A-PREWEIGH' and 
                           LotMaster.Itemkey = '{1}'
                    ", lotno, itemkey)
                    lotdetails = msdb.SelectDataScalar(lotdetails_qry)


                    'Update LotMaster
                    Dim newqty As Double
                    Dim pinewqty As Double
                    If Double.TryParse(qty, newqty) Then
                        newqty = CDbl(lotdetails("QtyCommitSales")) + CDbl(qty)
                        pinewqty = CDbl(pnitem("SerLotQty")) + CDbl(qty)
                        newqty = Math.Round(CDbl(newqty), 6, MidpointRounding.AwayFromZero)
                        pinewqty = Math.Round(CDbl(pinewqty), 6, MidpointRounding.AwayFromZero)

                    End If

                    'check if LotTransactionLock exist
                    Dim lts = String.Format("
                        Select * from LotTransactionLock
                        where 
                           DocNo = '{0}' and 
                           TranType = '5' and 
                           DocLineNo = '{1}'
                    ", batchno, pnitem("Lineid"))
                    Dim ltsdata As Dictionary(Of String, Object)
                    ltsdata = msdb.SelectDataScalar(lts)

                    If ltsdata.Count = 0 Then
                        'Add LotTransactionLock
                        Dim uLtl As New Dictionary(Of String, String)
                        uLtl.Add("DocNo", row.Cells("batchno").Value.ToString())
                        uLtl.Add("DocLineNo", pnitem("Lineid"))
                        uLtl.Add("TranType", "5")
                        uLtl.Add("RecDate", mydate)
                        uLtl.Add("RecUserId", myuserinfo.Item("uname").ToString)
                        msdb.Create("LotTransactionLock", uLtl)
                    End If

                    'Update PNITEM


                    Dim upi As New Dictionary(Of String, String)
                    Dim uLt As New Dictionary(Of String, String)
                    Dim myqty As Double = 0
                    myqty = qty

                    'If pinewqty >= CDbl(pnitem("StdQtyDispUom")) Then
                    '    upi.Add("Status", "A")
                    '    upi.Add("SerLotQty", StdQtyDispUom)
                    '    upi.Add("AllocQty", StdQtyDispUom)
                    'Else
                    '    upi.Add("SerLotQty", pinewqty)
                    '    upi.Add("AllocQty", pinewqty)
                    'End If

                    Dim wpi As String = String.Format("ItemKey = '{0}' and BatchNo = '{1}' and LineTyp= 'FI'", row.Cells("ItemKey").Value.ToString(), row.Cells("batchno").Value.ToString())

                    'msdb.Update("PNITEM", upi, wpi)

                    'Create new Lot Transaction

                    Dim myexpiry As DateTime = DateTime.Parse(lotdetails("DateExpiry1").ToString)
                    Dim dlotno = NotNull(lotdetails("LotNo"), "")
                    Dim ditemkey = NotNull(lotdetails("ItemKey"), "")
                    Dim dbatchno = row.Cells("batchno").Value.ToString()
                    Dim dDocline = pnitem("Lineid")
                    Dim BinNo = lotdetails("BinNo")

                    'check if LotTransaction exist
                    Dim lt = String.Format("
                        Select * from LotTransaction
                        where 
                           LotNo = '{0}' and 
                           ItemKey = '{1}' and 
                           LocationKey = 'MAIN' and
                           IssueDocNo = '{2}' and
                           IssueDocLineNo ='{3}' and 
                           BinNo = '{4}'
                    ", dlotno, ditemkey, dbatchno, dDocline, BinNo)
                    Dim ltdata As Dictionary(Of String, Object)
                    ltdata = msdb.SelectDataScalar(lt)

                    If ltdata.Count > 0 Then
                        Dim dLotTranNo = ""

                        If ltdata.TryGetValue("LotTranNo", dLotTranNo) Then

                            If CDbl(x_txtwtfr) <= CDbl(x_txttotalwt) And CDbl(x_txtwtto) >= CDbl(x_txttotalwt) Then
                                'myqty = x_txtreqwt - (x_txttotalwt - (CDbl(qty) + CDbl(ltdata("QtyIssued"))))
                                'upi.Add("Status", "A")
                                'upi.Add("SerLotQty", StdQtyDispUom)

                                If (StdQtyDispUom - (pinewqty) <= 0.000001) Then
                                    myqty = x_txtreqwt - (x_txttotalwt - (CDbl(qty) + CDbl(ltdata("QtyIssued"))))
                                    upi.Add("Status", "A")
                                    upi.Add("SerLotQty", StdQtyDispUom)
                                Else
                                    myqty = x_txtreqwt
                                    upi.Add("SerLotQty", x_txtreqwt)
                                End If


                                ' upi.Add("AllocQty", StdQtyDispUom)
                            Else
                                myqty = CDbl(ltdata("QtyIssued")) + CDbl(qty)
                                upi.Add("SerLotQty", pinewqty)
                                'upi.Add("AllocQty", pinewqty)
                            End If

                            uLt.Add("QtyIssued", myqty)
                            msdb.Update("PNITEM", upi, wpi)
                            msdb.Update("LotTransaction", uLt, String.Format("LotTranNo = '{0}'", dLotTranNo))

                        End If

                    Else

                        'uLt.Add("LotTranNo", lotdetails())
                        uLt.Add("LotNo", NotNull(lotdetails("LotNo"), ""))
                        uLt.Add("ItemKey", NotNull(lotdetails("ItemKey"), ""))
                        uLt.Add("LocationKey", NotNull(lotdetails("LocationKey"), ""))
                        uLt.Add("DateExpiry", NotNull(myexpiry.ToString("MM/dd/yyyy HH:mm"), ""))
                        uLt.Add("ReceiptDocNo", NotNull(lotdetails("DocumentNo"), ""))
                        uLt.Add("ReceiptDocLineNo", NotNull(lotdetails("DocumentLineNo"), ""))
                        uLt.Add("VendorLotNo", NotNull(lotdetails("VendorLotNo"), ""))
                        uLt.Add("TempQty", "0")
                        uLt.Add("QtyForLotAssignment", "0")
                        uLt.Add("BinNo", lotdetails("BinNo"))
                        uLt.Add("TransactionType", "5")
                        uLt.Add("QtyReceived", "0")
                        uLt.Add("IssueDocNo", row.Cells("batchno").Value.ToString())
                        uLt.Add("IssueDocLineNo", pnitem("Lineid"))
                        uLt.Add("IssueDate", mydate)
                        'uLt.Add("QtyIssued", myqty)
                        uLt.Add("RecUserid", myuserinfo.Item("uname").ToString)
                        uLt.Add("RecDate", mydate)
                        uLt.Add("Processed", "N")
                        uLt.Add("QtyUsed", "0")

                        If CDbl(x_txtwtfr) <= CDbl(x_txttotalwt) And CDbl(x_txtwtto) >= CDbl(x_txttotalwt) Then
                            'myqty = x_txtreqwt - (x_txttotalwt - CDbl(qty))
                            'upi.Add("Status", "A")
                            'upi.Add("SerLotQty", StdQtyDispUom)

                            If (StdQtyDispUom - (pinewqty) <= 0.000001) Then
                                myqty = x_txtreqwt - (x_txttotalwt - CDbl(qty))
                                upi.Add("Status", "A")
                                upi.Add("SerLotQty", StdQtyDispUom)
                            Else
                                myqty = x_txtreqwt
                                upi.Add("SerLotQty", x_txtreqwt)
                            End If

                            ' upi.Add("AllocQty", StdQtyDispUom)
                        Else
                            upi.Add("SerLotQty", pinewqty)
                            ' upi.Add("AllocQty", pinewqty)
                        End If

                        uLt.Add("QtyIssued", myqty)
                        msdb.Update("PNITEM", upi, wpi)
                        msdb.Create("LotTransaction", uLt)

                    End If

                    newqty = CDbl(lotdetails("QtyCommitSales")) + myqty
                    Dim uLm As New Dictionary(Of String, String)
                    uLm.Add("QtyCommitSales", newqty)
                    Dim wLm As String = String.Format("LotNo = '{0}' and ItemKey = '{1}' and LocationKey = 'MAIN' and BinNo = '{2}'", lotdetails("LotNo"), lotdetails("ItemKey"), lotdetails("BinNo"))
                    msdb.Update("LotMaster", uLm, wLm)

                    sum += value


                End If
            Else
                ' The cell value cannot be parsed to a double, handle the error
                MessageBox.Show("Error: invalid cell value")


            End If


        Next
        DataGridView1.Rows.Clear()
        txtItemBarcode.Clear()
        txtreqwt.Text = 0
        txtwtfr.Text = 0
        txtwtto.Text = 0
        txtactualwt.Text = 0
        txttotalwt.Text = 0

        strlotno = ""
        myitemkey = ""
        Dateexpiry = ""



        MsgBox("Successfully Updated", MsgBoxStyle.Information)
        Me.Close()
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
    Private Sub txtactualwt_TextChanged(sender As Object, e As EventArgs) Handles txtactualwt.TextChanged
        changetotal()
        Timer2.Stop()
        Timer2.Start()
    End Sub

    Private Sub Button2_Click_1(sender As Object, e As EventArgs) Handles Button2.Click

        Timer2.Stop()
        'Dim sum As Double = 0
        'Dim mdb As New MySQL()
        'Dim nwf_printer As Object = myreg("nwf_printer")
        'Dim zpl As New ZPLConnection
        'Dim c As New BarcodePrinter
        'Button1_Click(sender, e)
        'txtactualwt.Text = 0
        'For Each row As DataGridViewRow In DataGridView1.Rows

        '    If row.Cells("Qty").Value.ToString <> "" And row.Cells("Qty").Value.ToString <> "0" Then
        '        Dim zitemkey = row.Cells("ItemKey").Value.ToString()
        '        Dim zlotno = row.Cells("LotNo").Value.ToString()
        '        Dim zqty = row.Cells("Qty").Value.ToString()
        '        Dim zbatchno = row.Cells("batchno").Value.ToString()
        '        Dim myid = row.Cells("partialid").Value.ToString()


        '        If row.Cells("statflag").Value.ToString = "N" Then

        '            ' Do something with each row, for example:
        '            Dim mdata As New Dictionary(Of String, String)
        '            mdata.Add("runno", row.Cells("runno").Value.ToString())
        '            mdata.Add("itemkey", row.Cells("ItemKey").Value.ToString())
        '            mdata.Add("lotno", row.Cells("LotNo").Value.ToString())
        '            mdata.Add("binno", "") 'row.Cells("Qty").Value.ToString()
        '            mdata.Add("qty", row.Cells("Qty").Value.ToString())
        '            mdata.Add("batchno", row.Cells("batchno").Value.ToString())
        '            myid = mdb.Create("tbl_rm_allocate_partial", mdata)

        '            Dim value As Double
        '            If Double.TryParse(row.Cells("Qty").Value.ToString(), value) Then
        '                ' The cell value can be parsed to a double, do something with it
        '                ' For example, add it to a running total
        '                sum += value
        '            Else
        '                ' The cell value cannot be parsed to a double, handle the error
        '                MessageBox.Show("Error: invalid cell value")

        '            End If
        '            Dim zbarcode = "nwf-pb-" & myid.PadLeft(11, "0")
        '            'zpl.SendZplOverUsb(nwf_printer, zitemkey, zqty, zlotno, zbarcode, zbatchno)
        '            Dim mystring As String

        '            mystring = String.Format("
        '                    ^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR4,4~SD15^JUS^LRN^CI0^XZ
        '                    ^XA
        '                    ^MMT
        '                    ^PW812
        '                    ^LL0203
        '                    ^LS0
        '                    ^FT788,164^A0I,39,36^FH\^FDItem key:^FS
        '                    ^FT788,120^A0I,39,36^FH\^FDLot No:^FS
        '                    ^FT788,79^A0I,39,36^FH\^FDQuantity:^FS
        '                    ^FT636,164^A0I,39,36^FH\^FD{0}^FS
        '                    ^FT636,79^A0I,39,36^FH\^FD{2}^FS
        '                    ^FT636,119^A0I,39,36^FH\^FD{1}^FS
        '                    ^BY2,3,44^FT554,27^BCI,,Y,N
        '                    ^FD{3}^FS
        '                    ^FT344,163^A0I,39,36^FH\^FDBatch No:^FS
        '                    ^FT193,163^A0I,39,36^FH\^FD{4}^FS
        '                    ^PQ1,0,1,Y^XZ
        '                    ", zitemkey, zlotno, zqty, zbarcode, zbatchno)

        '            BarcodePrinter.SendZplOverUsb(mystring, nwf_printer)
        '        End If
        '    End If
        'Next
        'DataGridView1.Rows.Clear()
        'txtItemBarcode.Clear()
        'txtreqwt.Text = 0
        'txtwtfr.Text = 0
        'txtwtto.Text = 0
        'txtactualwt.Text = 0
        'txttotalwt.Text = 0

        'strlotno = ""
        'myitemkey = ""
        'Dateexpiry = ""

        'MsgBox("Successfully Updated", MsgBoxStyle.Information)
        'Me.Close()

        Dim x_txttotalwt = Math.Round(CDbl(txttotalwt.Text), 6, MidpointRounding.AwayFromZero)
        Dim x_txtwtfr = Math.Round(CDbl(txtwtfr.Text), 6, MidpointRounding.AwayFromZero)
        Dim x_txtwtto = Math.Round(CDbl(txtwtto.Text), 6, MidpointRounding.AwayFromZero)
        Dim x_txtreqwt = Math.Round(CDbl(txtreqwt.Text), 6, MidpointRounding.AwayFromZero)

        Dim sum As Double = 0
        Dim mdb As New MySQL()
        Dim msdb As New MsSQL()
        Dim nwf_printer As Object = myreg("nwf_printer")
        Dim value As Double
        Dim c As New BarcodePrinter
        ' Button1.PerformClick()
        Button1_Click()

        Dim mytotal_qty As Double = 0

        Dim now As DateTime = DateTime.Now
        Dim mydate As String = now.ToString("MM/dd/yyyy HH:mm")
        Dim myuserinfo = UserInfo.getUserinfo()
        For Each row As DataGridViewRow In DataGridView1.Rows

            If row.Cells("Qty").Value.ToString <> "" And Double.TryParse(row.Cells("Qty").Value.ToString(), value) Then
                Dim zitemkey = row.Cells("ItemKey").Value.ToString()
                Dim zlotno = row.Cells("LotNo").Value.ToString()
                Dim zqty = row.Cells("Qty").Value.ToString()
                Dim zbatchno = row.Cells("batchno").Value.ToString()
                Dim myid = row.Cells("partialid").Value.ToString()

                Dim itemkey = row.Cells("ItemKey").Value.ToString()
                Dim batchno = row.Cells("batchno").Value.ToString()
                Dim lotno = row.Cells("LotNo").Value.ToString()
                Dim qty = row.Cells("Qty").Value.ToString()

                mytotal_qty += CDbl(zqty)

                If row.Cells("statflag").Value.ToString = "N" Then



                    ' Do something with each row, for example:
                    Dim mdata As New Dictionary(Of String, String)
                    mdata.Add("runno", row.Cells("runno").Value.ToString())
                    mdata.Add("itemkey", row.Cells("ItemKey").Value.ToString())
                    mdata.Add("lotno", row.Cells("LotNo").Value.ToString())
                    mdata.Add("binno", "A-PREWEIGH") 'row.Cells("Qty").Value.ToString()
                    mdata.Add("qty", qty)
                    mdata.Add("batchno", row.Cells("batchno").Value.ToString())
                    mdata.Add("required_qty", x_txtreqwt)
                    myid = mdb.Create("tbl_rm_allocate_partial", mdata)

                    Dim pnitem As New Dictionary(Of String, Object)
                    Dim pnitem_qry = String.Format("Select * from PNITEM where ItemKey = '{0}' and BatchNo = '{1}' and LineTyp= 'FI'", row.Cells("ItemKey").Value.ToString(), row.Cells("batchno").Value.ToString())
                    pnitem = msdb.SelectDataScalar(pnitem_qry)

                    Dim lotdetails As Dictionary(Of String, Object)
                    Dim lotdetails_qry = String.Format("
                        Select LotMaster.*,INLOC.inclasskey , Convert(date,LotMaster.DateExpiry) as DateExpiry1
                            from LotMaster
                        left join INLOC on INLOC.itemkey = LotMaster.Itemkey and INLOC.Location = LotMaster.LocationKey
                        where 
                           LotMaster.LotNo = '{0}' and 
                           LotMaster.BinNo = 'A-PREWEIGH' and 
                           LotMaster.Itemkey = '{1}'
                    ", row.Cells("LotNo").Value.ToString(), row.Cells("ItemKey").Value.ToString())
                    lotdetails = msdb.SelectDataScalar(lotdetails_qry)


                    'Update LotMaster
                    Dim newqty As Double
                    Dim pinewqty As Double
                    If Double.TryParse(qty, newqty) Then
                        newqty = CDbl(lotdetails("QtyCommitSales")) + CDbl(qty)
                        pinewqty = CDbl(pnitem("SerLotQty")) + CDbl(qty)

                        newqty = Math.Round(CDbl(newqty), 6, MidpointRounding.AwayFromZero)
                        pinewqty = Math.Round(CDbl(pinewqty), 6, MidpointRounding.AwayFromZero)

                    End If




                    'check if LotTransactionLock exist
                    Dim lts = String.Format("
                        Select * from LotTransactionLock
                        where 
                           DocNo = '{0}' and 
                           TranType = '5' and 
                           DocLineNo = '{1}'
                    ", row.Cells("batchno").Value.ToString(), pnitem("Lineid"))
                    Dim ltsdata As Dictionary(Of String, Object)
                    ltsdata = msdb.SelectDataScalar(lts)

                    If ltsdata.Count = 0 Then
                        'Add LotTransactionLock
                        Dim uLtl As New Dictionary(Of String, String)
                        uLtl.Add("DocNo", row.Cells("batchno").Value.ToString())
                        uLtl.Add("DocLineNo", pnitem("Lineid"))
                        uLtl.Add("TranType", "5")
                        uLtl.Add("RecDate", mydate)
                        uLtl.Add("RecUserId", myuserinfo.Item("uname").ToString)
                        msdb.Create("LotTransactionLock", uLtl)
                    End If

                    'Update PNITEM

                    Dim upi As New Dictionary(Of String, String)
                    Dim uLt As New Dictionary(Of String, String)
                    Dim myqty As Double = 0
                    myqty = qty


                    'If pinewqty >= CDbl(pnitem("StdQtyDispUom")) Then
                    '    upi.Add("Status", "A")
                    '    upi.Add("SerLotQty", StdQtyDispUom)
                    '    upi.Add("AllocQty", StdQtyDispUom)
                    'Else
                    '    upi.Add("SerLotQty", pinewqty)
                    '    upi.Add("AllocQty", pinewqty)
                    'End If

                    Dim wpi As String = String.Format("ItemKey = '{0}' and BatchNo = '{1}' and LineTyp= 'FI'", row.Cells("ItemKey").Value.ToString(), row.Cells("batchno").Value.ToString())



                    'Create new Lot Transaction

                    Dim myexpiry As DateTime = DateTime.Parse(lotdetails("DateExpiry1").ToString)
                    Dim dlotno = NotNull(lotdetails("LotNo"), "")
                    Dim ditemkey = NotNull(lotdetails("ItemKey"), "")
                    Dim dbatchno = row.Cells("batchno").Value.ToString()
                    Dim dDocline = pnitem("Lineid")
                    Dim BinNo = lotdetails("BinNo")

                    'check if LotTransaction exist
                    Dim lt = String.Format("
                        Select * from LotTransaction
                        where 
                           LotNo = '{0}' and 
                           ItemKey = '{1}' and 
                           LocationKey = 'MAIN' and
                           IssueDocNo = '{2}' and
                           IssueDocLineNo ='{3}' and 
                           BinNo = '{4}'
                    ", dlotno, ditemkey, dbatchno, dDocline, BinNo)
                    Dim ltdata As Dictionary(Of String, Object)
                    ltdata = msdb.SelectDataScalar(lt)

                    If ltdata.Count > 0 Then
                        Dim dLotTranNo = ""

                        If ltdata.TryGetValue("LotTranNo", dLotTranNo) Then


                            If CDbl(x_txtwtfr) <= CDbl(x_txttotalwt) And CDbl(x_txtwtto) >= CDbl(x_txttotalwt) Then
                                'myqty = x_txtreqwt - (x_txttotalwt - (CDbl(qty) + CDbl(ltdata("QtyIssued"))))
                                'upi.Add("Status", "A")
                                'upi.Add("SerLotQty", StdQtyDispUom)

                                If (StdQtyDispUom - (pinewqty) <= 0.000001) Then
                                    myqty = x_txtreqwt - (x_txttotalwt - (CDbl(qty) + CDbl(ltdata("QtyIssued"))))
                                    upi.Add("Status", "A")
                                    upi.Add("SerLotQty", StdQtyDispUom)
                                Else
                                    myqty = x_txtreqwt
                                    upi.Add("SerLotQty", x_txtreqwt)
                                End If


                                ' upi.Add("AllocQty", StdQtyDispUom)
                            Else
                                myqty = CDbl(ltdata("QtyIssued")) + CDbl(qty)
                                upi.Add("SerLotQty", pinewqty)
                                'upi.Add("AllocQty", pinewqty)
                            End If



                            uLt.Add("QtyIssued", myqty)
                            msdb.Update("PNITEM", upi, wpi)
                            msdb.Update("LotTransaction", uLt, String.Format("LotTranNo = '{0}'", dLotTranNo))
                        End If

                        'uLt.Add("LotTranNo", lotdetails())
                        'uLt.Add("LotNo", NotNull(lotdetails("LotNo"), ""))
                        'uLt.Add("ItemKey", NotNull(lotdetails("ItemKey"), ""))
                        'uLt.Add("LocationKey", NotNull(lotdetails("LocationKey"), ""))
                        'uLt.Add("DateExpiry", NotNull(myexpiry.ToString("MM/dd/yyyy HH:mm"), ""))
                        'uLt.Add("ReceiptDocNo", NotNull(lotdetails("DocumentNo"), ""))
                        'uLt.Add("ReceiptDocLineNo", NotNull(lotdetails("DocumentLineNo"), ""))
                        'uLt.Add("VendorLotNo", NotNull(lotdetails("VendorLotNo"), ""))
                        'uLt.Add("TempQty", "0")
                        'uLt.Add("QtyForLotAssignment", "0")
                        'uLt.Add("BinNo", lotdetails("BinNo"))
                        'uLt.Add("TransactionType", "5")
                        'uLt.Add("QtyReceived", "0")
                        'uLt.Add("IssueDocNo", row.Cells("batchno").Value.ToString())
                        'uLt.Add("IssueDocLineNo", pnitem("Lineid"))
                        'uLt.Add("IssueDate", mydate)
                        'uLt.Add("QtyIssued", myqty)
                        'uLt.Add("RecUserid", myuserinfo.Item("uname").ToString)
                        'uLt.Add("RecDate", mydate)
                        'uLt.Add("Processed", "N")
                        'uLt.Add("QtyUsed", "0")
                        'msdb.Create("LotTransaction", uLt)

                    Else

                        'uLt.Add("LotTranNo", lotdetails())
                        uLt.Add("LotNo", NotNull(lotdetails("LotNo"), ""))
                        uLt.Add("ItemKey", NotNull(lotdetails("ItemKey"), ""))
                        uLt.Add("LocationKey", NotNull(lotdetails("LocationKey"), ""))
                        uLt.Add("DateExpiry", NotNull(myexpiry.ToString("MM/dd/yyyy HH:mm"), ""))
                        uLt.Add("ReceiptDocNo", NotNull(lotdetails("DocumentNo"), ""))
                        uLt.Add("ReceiptDocLineNo", NotNull(lotdetails("DocumentLineNo"), ""))
                        uLt.Add("VendorLotNo", NotNull(lotdetails("VendorLotNo"), ""))
                        uLt.Add("TempQty", "0")
                        uLt.Add("QtyForLotAssignment", "0")
                        uLt.Add("BinNo", lotdetails("BinNo"))
                        uLt.Add("TransactionType", "5")
                        uLt.Add("QtyReceived", "0")
                        uLt.Add("IssueDocNo", row.Cells("batchno").Value.ToString())
                        uLt.Add("IssueDocLineNo", pnitem("Lineid"))
                        uLt.Add("IssueDate", mydate)
                        'uLt.Add("QtyIssued", myqty)
                        uLt.Add("RecUserid", myuserinfo.Item("uname").ToString)
                        uLt.Add("RecDate", mydate)
                        uLt.Add("Processed", "N")
                        uLt.Add("QtyUsed", "0")

                        If CDbl(x_txtwtfr) <= CDbl(x_txttotalwt) And CDbl(x_txtwtto) >= CDbl(x_txttotalwt) Then
                            'myqty = x_txtreqwt - (x_txttotalwt - CDbl(qty))
                            'upi.Add("Status", "A")
                            'upi.Add("SerLotQty", StdQtyDispUom)

                            If (StdQtyDispUom - (pinewqty) <= 0.000001) Then
                                myqty = x_txtreqwt - (x_txttotalwt - CDbl(qty))
                                upi.Add("Status", "A")
                                upi.Add("SerLotQty", StdQtyDispUom)
                            Else
                                myqty = x_txtreqwt
                                upi.Add("SerLotQty", x_txtreqwt)
                            End If

                            ' upi.Add("AllocQty", StdQtyDispUom)
                        Else
                            upi.Add("SerLotQty", pinewqty)
                            ' upi.Add("AllocQty", pinewqty)
                        End If

                        uLt.Add("QtyIssued", myqty)
                        msdb.Update("PNITEM", upi, wpi)
                        msdb.Create("LotTransaction", uLt)

                    End If

                    newqty = CDbl(lotdetails("QtyCommitSales")) + myqty
                    Dim uLm As New Dictionary(Of String, String)
                    uLm.Add("QtyCommitSales", newqty)
                    Dim wLm As String = String.Format("LotNo = '{0}' and ItemKey = '{1}' and LocationKey = 'MAIN' and BinNo = '{2}'", lotdetails("LotNo"), lotdetails("ItemKey"), lotdetails("BinNo"))
                    msdb.Update("LotMaster", uLm, wLm)


                    ' The cell value can be parsed to a double, do something with it
                    ' For example, add it to a running total
                    sum += value

                    Dim allergen
                    Dim alq = String.Format("Select * from v_item_allergen where itemkey = '{0}'", zitemkey)

                    Dim ald = msdb.SelectDataScalar(alq)
                    'allergen = ald("allergenx")
                    If ald.Count > 0 And ald.TryGetValue("allergen", allergen) Then
                        If IsDBNull(allergen) Then
                            allergen = ""
                        Else
                            allergen = ald("allergen").ToString
                        End If

                    Else
                        allergen = ""
                    End If



                    Dim zbarcode = "nwf-pb-" & myid.PadLeft(11, "0")
                    Dim mystring As String
                    'mystring = String.Format("
                    '        ^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR4,4~SD15^JUS^LRN^CI0^XZ
                    '        ^XA
                    '        ^MMT
                    '        ^PW812
                    '        ^LL0203
                    '        ^LS0
                    '        ^FT788,164^A0I,39,36^FH\^FDItem key:^FS
                    '        ^FT788,120^A0I,39,36^FH\^FDLot No:^FS
                    '        ^FT788,79^A0I,39,36^FH\^FDQuantity:^FS
                    '        ^FT636,164^A0I,39,36^FH\^FD{0}^FS
                    '        ^FT636,79^A0I,39,36^FH\^FD{2}^FS
                    '        ^FT636,119^A0I,39,36^FH\^FD{1}^FS
                    '        ^BY2,3,44^FT554,27^BCI,,Y,N
                    '        ^FD{3}^FS
                    '        ^FT344,163^A0I,39,36^FH\^FDBatch No:^FS
                    '        ^FT193,163^A0I,39,36^FH\^FD{4}^FS
                    '        ^PQ1,0,1,Y^XZ
                    '        ", zitemkey, zlotno, zqty, zbarcode, zbatchno)

                    mystring = String.Format("
                            ^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR4,4~SD15^JUS^LRN^CI0^XZ
                            ^XA
                            ^MMT
                            ^PW812
                            ^LL0203
                            ^LS0
                            ^FT788,164^A0I,39,36^FH\^FDItem key:^FS
                            ^FT788,120^A0I,39,36^FH\^FDLot No:^FS
                            ^FT788,79^A0I,39,36^FH\^FDQuantity:^FS
                            ^FT636,164^A0I,39,36^FH\^FD{0}^FS
                            ^FT636,79^A0I,39,36^FH\^FD{2}^FS
                            ^FT636,119^A0I,39,36^FH\^FD{1}^FS
                            ^BY2,3,44^FT554,27^BCI,,Y,N
                            ^FD{3}^FS
                            ^FT344,163^A0I,39,36^FH\^FDBatch No:^FS
                            ^FT193,163^A0I,39,36^FH\^FD{4}^FS
                            ^FT342,79^A0I,39,36^FH\^FDAllergen:^FS
                            ^FT206,79^A0I,39,36^FH\^FD{5}^FS
                            ^PQ1,0,1,Y^XZ

                            ", zitemkey, zlotno, zqty, zbarcode, zbatchno, allergen)
                    BarcodePrinter.SendZplOverUsb(mystring, nwf_printer)
                End If
            Else
                ' The cell value cannot be parsed to a double, handle the error
                MessageBox.Show("Error: invalid cell value")


            End If


        Next
        DataGridView1.Rows.Clear()
        txtItemBarcode.Clear()
        txtreqwt.Text = 0
        txtwtfr.Text = 0
        txtwtto.Text = 0
        txtactualwt.Text = 0
        txttotalwt.Text = 0

        strlotno = ""
        myitemkey = ""
        Dateexpiry = ""



        MsgBox("Successfully Updated", MsgBoxStyle.Information)
        Me.Close()
    End Sub

    Private Sub DataGridView1_CellDoubleClick(sender As Object, e As DataGridViewCellEventArgs) Handles DataGridView1.CellDoubleClick
        If e.RowIndex >= 0 Then
            Dim row As DataGridViewRow = DataGridView1.Rows(e.RowIndex)
            Dim mystat As String = row.Cells("statflag").Value
            Dim msdb As New MsSQL()
            If mystat <> "N" Then

                Dim wprint = MsgBox("Do you want to re-print the sticker", MsgBoxStyle.YesNo)
                If wprint = vbYes Then
                    Dim zpl As New ZPLConnection

                    Dim zitemkey = row.Cells("ItemKey").Value.ToString()
                    Dim zlotno = row.Cells("LotNo").Value.ToString()
                    Dim zqty = row.Cells("Qty").Value.ToString()
                    Dim zbatchno = row.Cells("batchno").Value.ToString()
                    Dim myid = row.Cells("partialid").Value.ToString()
                    Dim zbarcode = "nwf-pb-" & myid.PadLeft(11, "0")

                    Dim mystring As String
                    'Dim myreg As New RegistryCRUD()

                    Try
                        Dim readDictionary As Dictionary(Of String, Object) = ReadXmlFileToDictionary()
                        Dim nwf_printer As Object = readDictionary("nwf_printer")

                        Dim allergen = ""
                        Dim alq = String.Format("Select * from v_item_allergen where itemkey = '{0}'", zitemkey)

                        Dim ald = msdb.SelectDataScalar(alq)
                        'allergen = ald("allergenx")
                        If ald.Count > 0 And ald.TryGetValue("allergen", allergen) Then
                            If IsDBNull(allergen) Then
                                allergen = ""
                            Else
                                allergen = ald("allergen").ToString
                            End If

                        Else
                            allergen = ""
                        End If

                        If nwf_printer IsNot Nothing Then
                            mystring = String.Format("
                            ^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR4,4~SD15^JUS^LRN^CI0^XZ
                            ^XA
                            ^MMT
                            ^PW812
                            ^LL0203
                            ^LS0
                            ^FT788,164^A0I,39,36^FH\^FDItem key:^FS
                            ^FT788,120^A0I,39,36^FH\^FDLot No:^FS
                            ^FT788,79^A0I,39,36^FH\^FDQuantity:^FS
                            ^FT636,164^A0I,39,36^FH\^FD{0}^FS
                            ^FT636,79^A0I,39,36^FH\^FD{2}^FS
                            ^FT636,119^A0I,39,36^FH\^FD{1}^FS
                            ^BY2,3,44^FT554,27^BCI,,Y,N
                            ^FD{3}^FS
                            ^FT344,163^A0I,39,36^FH\^FDBatch No:^FS
                            ^FT193,163^A0I,39,36^FH\^FD{4}^FS
                            ^FT342,79^A0I,39,36^FH\^FDAllergen:^FS
                            ^FT206,79^A0I,39,36^FH\^FD{5}^FS
                            ^PQ1,0,1,Y^XZ

                            ", zitemkey, zlotno, zqty, zbarcode, zbatchno, allergen)

                            BarcodePrinter.SendZplOverUsb(mystring, nwf_printer)

                        End If


                    Catch ex As Exception
                        LogError(ex)

                    End Try

                End If

            End If

        End If

    End Sub

    Private Sub Timer1_Tick(sender As Object, e As EventArgs) Handles Timer1.Tick
        'Dim data As String
        'Dim mydata As String
        'Dim myval As Double
        'txtactualwt.Text = frmMainBeta.ssport.serialdata()


        'If is_connected Then


        '    If is_connected = True Then

        '        Try
        '            Dim confac As New Integer

        '            data = serialPort.ReadExisting()
        '            data = data.Trim()

        '            Dim chkstr As String()
        '            Dim mystring As String

        '            If myscale = "SCALE 2" Then

        '                confac = CInt(myreg("scale2_conversion"))
        '                If data.IndexOf("+x!") >= 0 Then
        '                    chkstr = data.Split("+x!")
        '                    mystring = chkstr(1)

        '                ElseIf data.IndexOf("+p!") >= 0 Then
        '                    chkstr = data.Split("+x!")
        '                    mystring = chkstr(1)

        '                Else
        '                    mystring = data
        '                End If

        '                mydata = Regex.Replace(mystring, "[^0-9]", "")
        '                Dim mydbl As New Double
        '                If data <> "" Then


        '                    If Double.TryParse(mydata, mydbl) Then
        '                        myval = CDbl(mydata) / confac
        '                        mydbl = Math.Round(myval, 6)
        '                        txtactualwt.Text = mydbl.ToString("0.000000")
        '                    End If

        '                    ' myval = CDbl(mydata) / 1000000000000
        '                    'txtactualwt.Text = Math.Round(myval, 6)
        '                    'TextBox1.Text = i
        '                End If

        '            Else
        '                confac = CInt(myreg("scale1_conversion"))

        '                If data.IndexOf("+x!") >= 0 Then
        '                    chkstr = data.Split("+x!")
        '                    mystring = chkstr(1)

        '                ElseIf data.IndexOf("+p!") >= 0 Then
        '                    chkstr = data.Split("+x!")
        '                    mystring = chkstr(1)

        '                Else
        '                    mystring = data
        '                End If

        '                mydata = Regex.Replace(mystring, "[^0-9]", "")
        '                Dim mydbl As New Double
        '                If data <> "" Then


        '                    If Double.TryParse(mydata, mydbl) Then
        '                        myval = CDbl(mydata) / confac
        '                        mydbl = Math.Round(myval, 6)
        '                        txtactualwt.Text = mydbl.ToString("0.000000")
        '                    End If

        '                    ' myval = CDbl(mydata) / 1000000000000
        '                    'txtactualwt.Text = Math.Round(myval, 6)
        '                    'TextBox1.Text = i
        '                End If

        '            End If




        '        Catch ex As Exception
        '            ' LogError(ex)
        '            is_connected = False
        '            sstWtscaleStatus.Text = "Disconnected"
        '            sconnect()
        '        End Try

        '    End If

        '    sstWtscaleStatus.Text = "Connected"

        'Else
        '    sstWtscaleStatus.Text = "Disconnected"
        '    sconnect()
        'End If
    End Sub

    Private Sub txtItemBarcode_TextChanged(sender As Object, e As EventArgs) Handles txtItemBarcode.TextChanged
        itemBarcode.PerformClick()
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

    Private Sub frmFetchWt_FormClosing(sender As Object, e As FormClosingEventArgs) Handles Me.FormClosing

    End Sub

    Private Sub DataGridView1_CellContentClick(sender As Object, e As DataGridViewCellEventArgs) Handles DataGridView1.CellContentClick

    End Sub

    Private Sub Button1_Click(sender As Object, e As EventArgs) Handles Button1.Click

    End Sub
End Class