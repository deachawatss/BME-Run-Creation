Imports System.Drawing.Printing
Imports System.Text
Imports Zebra.Sdk.Comm
Imports Zebra.Sdk.Printer.Discovery
Imports System.Data.SqlClient
Imports CrystalDecisions.CrystalReports.Engine
Imports CrystalDecisions.Shared
Imports System.Data

Public Class frmPrintModal
    Dim WithEvents pd As New PrintDocument
    Dim PPD As New PrintPreviewDialog
    Dim longpaper As Integer
    Dim objRpt As New Mobile_Partial_Picklist_Daily

    Private Sub Button1_Click(sender As Object, e As EventArgs) Handles Button1.Click
        Dim zpl As New ZPLConnection
        'PPD.Document = PrintDocument1
        'PPD.ShowDialog()
        '\\?\usb#vid_0a5f&pid_0185#d6j225007636#{28d78fad-5a12-11d1-ae5b-0000f803a8c2}
        '\\?\usb#vid_0a5f&pid_0185#d6j225007636#{28d78fad-5a12-11d1-ae5b-0000f803a8c2}
        'zpl.SendZplOverUsb("\\?\usb#vid_0a5f&pid_0185#d6j225007636#{28d78fad-5a12-11d1-ae5b-0000f803a8c2}", )
        'zpl.UsbDriverlessTest()
    End Sub


    Private Sub PrintDocument1_PrintPage(sender As Object, e As PrintPageEventArgs) Handles PrintDocument1.PrintPage
        Dim f8 As New Font("Calibri", 8, FontStyle.Regular)
        Dim f9 As New Font("Calibri", 9, FontStyle.Regular)
        Dim f10 As New Font("Calibri", 10, FontStyle.Regular)
        Dim f11 As New Font("Calibri", 11, FontStyle.Regular)
        Dim f12 As New Font("Calibri", 12, FontStyle.Regular)
        Dim f13 As New Font("Calibri", 13, FontStyle.Regular)
        Dim f14 As New Font("Calibri", 14, FontStyle.Regular)

        Dim leftmargin As Integer = PrintDocument1.DefaultPageSettings.Margins.Left
        Dim centermargin As Integer = PrintDocument1.DefaultPageSettings.PaperSize.Width / 2
        Dim rightmargin As Integer = PrintDocument1.DefaultPageSettings.PaperSize.Width

        Dim right As New StringFormat
        Dim center As New StringFormat
        right.Alignment = StringAlignment.Far
        center.Alignment = StringAlignment.Center


        Dim line As String
        line = "--------------------------------------------------------"
        'e.Graphics.DrawString("ItemKey", f12, Brushes.Black, centermargin, 5, center)
        e.Graphics.DrawString("ItemKey", f8, Brushes.Black, 1, 1)
        'e.Graphics.DrawString("Lot No", f12, Brushes.Black, 5, 22)
        'e.Graphics.DrawString("Quantity", f12, Brushes.Black, 5, 39)
        Dim gbarcode As New MessagingToolkit.Barcode.BarcodeEncoder

        Try
            Dim barcodeimage As Image
            barcodeimage = New Bitmap(gbarcode.Encode(MessagingToolkit.Barcode.BarcodeFormat.Code128, "hey"))
            'e.Graphics.DrawImage(barcodeimage, 55, 55, 150, 20)
        Catch ex As Exception
            LogError(ex)

        End Try




        ' e.Graphics.DrawString(line, f8, Brushes.Black, centermargin, 70, center)
        ' e.Graphics.DrawString("Nosware Store", f8, Brushes.Black, centermargin, 20, center)

        'e.Graphics.DrawString(line, f8, Brushes.Black, centermargin, 75, center)
    End Sub

    Private Sub PrintDocument1_BeginPrint(sender As Object, e As PrintEventArgs) Handles PrintDocument1.BeginPrint
        Dim pagesetup As New PageSettings
        '   pagesetup.PaperSize = New PaperSize("Custom", 101.6, 25.4)
        PrintDocument1.DefaultPageSettings = pagesetup
    End Sub

    Private Sub Button2_Click(sender As Object, e As EventArgs) Handles Button2.Click
        ' Test the dictionary to XML file and XML file to dictionary functions
        Dim dictionary As New Dictionary(Of String, Object)()
        dictionary.Add("name", "John Smith")
        dictionary.Add("age", 30)

        ' Convert dictionary to XML file
        ConvertDictionaryToXmlFile(dictionary)
        Console.WriteLine("Dictionary converted to XML file successfully.")

        ' Read XML file into dictionary
        Dim readDictionary As Dictionary(Of String, Object) = ReadXmlFileToDictionary()
        If readDictionary IsNot Nothing Then
            Console.WriteLine("XML file read into dictionary successfully:")
            For Each kvp As KeyValuePair(Of String, Object) In readDictionary
                Console.WriteLine("Key: " & kvp.Key)
                'Console.WriteLine("Value: " & kvp.Value)
                Console.WriteLine()
            Next
        End If

        Console.WriteLine("Dictionary to XML file and XML file to dictionary conversion performed successfully.")
    End Sub

    Private Sub frmPrintModal_Load(sender As Object, e As EventArgs) Handles Me.Load

    End Sub
End Class