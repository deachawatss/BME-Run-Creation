Imports System.ComponentModel
Imports System.IO.Ports
Imports System.Text.RegularExpressions

Public Class frmScanToPrint

    Private myreg As Dictionary(Of String, Object) = ReadXmlFileToDictionary()


    Private Sub Button1_Click(sender As Object, e As EventArgs) Handles Button1.Click
        Dim readDictionary As Dictionary(Of String, Object) = ReadXmlFileToDictionary()
        Dim nwf_printer As Object = readDictionary("nwf_printer")
        Dim Barcode As Dictionary(Of String, String) = mybarcode(txtbarcode.Text)
        Dim itemkey As String = Barcode("02")
        Dim lotno As String = Barcode("10")
        Dim qty = txtqty.Text



        If nwf_printer IsNot Nothing Then
            Dim mystring = String.Format("
                            ^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR4,4~SD25^JUS^LRN^CI0^XZ
                            ^XA
                            ^MMT
                            ^PW812
                            ^LL0203
                            ^LS0
                            ^FT15,48^A0N,39,30^FH\^FDITEM KEY:^FS
                            ^BY1,3,62^FT232,175^BCN,,Y,N
                            ^FD>:{3}^FS
                            ^FT15,94^A0N,39,30^FH\^FDLOT NO:^FS
                            ^FT452,48^A0N,39,30^FH\^FDQUANTITY:^FS
                            ^FT452,94^A0N,39,30^FH\^FDBATCH NO:^FS
                            ^FT150,48^A0N,39,30^FH\^FD{0}^FS
                            ^FT120,94^A0N,39,30^FH\^FD{1}^FS
                            ^FT600,48^A0N,39,30^FH\^FD{2}^FS
                            ^FT600,94^A0N,39,30^FH\^FD{4}^FS
                            ^PQ1,0,1,Y^XZ
                            ", itemkey, lotno, qty, txtbarcode.Text, txtbatchno.Text)



            BarcodePrinter.SendZplOverUsb(mystring, nwf_printer)

        End If
    End Sub


    Private Sub txtbarcode_GotFocus(sender As Object, e As EventArgs) Handles txtbarcode.GotFocus
        txtbarcode.Clear()
    End Sub

    Private Sub frmScanToPrint_Load(sender As Object, e As EventArgs) Handles Me.Load
        txtscale.SelectedIndex = 0
        'frmMainBeta.ssport.Close()
        'frmMainBeta.ssport.connect("scale1")
        spr.Close()
        spr.connect("scale1", txtqty)
        Timer1.Interval = myreg("scale1_interval")
        Timer1.Start()
    End Sub

    Private Sub txtscale_SelectedIndexChanged(sender As Object, e As EventArgs) Handles txtscale.SelectedIndexChanged
        'frmMainBeta.ssport.Close()

        If txtscale.SelectedIndex = 0 Then
            'frmMainBeta.ssport.connect("scale1")
            spr.Close()
            spr.connect("scale1", txtqty)
            Timer1.Interval = myreg("scale1_interval")
        Else
            'frmMainBeta.ssport.connect("scale2")
            spr.Close()
            spr.connect("scale2", txtqty)
            Timer1.Interval = myreg("scale2_interval")
        End If
    End Sub


    Private Sub Timer1_Tick(sender As Object, e As EventArgs) Handles Timer1.Tick
        'txtqty.Text = frmMainBeta.ssport.serialdata()
    End Sub

End Class