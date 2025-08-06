Imports System.IO
Imports System.Drawing.Printing
Imports Zebra.Sdk.Comm
Imports System.Text
Imports Zebra.Sdk.Printer
Imports Zebra.Sdk.Printer.Discovery

Public Class BarcodePrinter
    Private myreg As Dictionary(Of String, Object) = ReadXmlFileToDictionary()
    Public Shared Sub PrintBarcodeIntermec(ByVal barcode As String, ByVal usbDriverName As String)
        Dim width As Integer = 3
        Dim height As Integer = 150
        Dim marginLeft As Integer = 100
        Dim marginTop As Integer = 100

        Dim command As String = String.Format("I8,A,001" & vbCrLf & "Q{0},6" & vbCrLf & "B2N,{1},{2},{3},U,{4}" & vbCrLf & "A{5},{6},0,3,1,1,N,'{4}'" & vbCrLf & "P1" & vbCrLf, marginLeft, marginTop, height, width, barcode, marginLeft, marginTop + height + 10)
        SendCommandToPrinter(command, usbDriverName)
    End Sub

    Public Shared Sub PrintBarcodeHoneywell(barcodeData As String, ByVal usbDriverName As String)
        Dim width As Integer = 3
        Dim height As Integer = 150
        Dim marginLeft As Integer = 100
        Dim marginTop As Integer = 100

        Dim command As String = String.Format("^XA^FO{0},{1}^BY{2}^B3N,N,{3},Y,N^FD{4}^FS^FO{5},{6}^AAN,30,20^FD{4}^FS^XZ", marginLeft, marginTop, width, height, barcodeData, marginLeft, marginTop + height + 10)
        SendCommandToPrinter(command, usbDriverName)
    End Sub

    Private Shared Sub SendCommandToPrinter(command As String, ByVal usbDriverName As String)

        Dim printDocument As New PrintDocument()
        printDocument.PrinterSettings.PrinterName = usbDriverName

        AddHandler printDocument.PrintPage, Sub(sender, e)
                                                e.Graphics.DrawString(command, New Font("Arial", 8), Brushes.Black, 0, 0)
                                                e.HasMorePages = False
                                            End Sub

        printDocument.Print()
    End Sub

    Private Shared Sub SendCommandToPrinterZebra(command As String)
        Dim printerName As String = "ZDesigner ZD421-203Dpi ZPL" ' Replace with the name of your printer

        Dim printDocument As New PrintDocument()
        printDocument.PrinterSettings.PrinterName = printerName

        AddHandler printDocument.PrintPage, Sub(sender, e)
                                                e.Graphics.DrawString(command, New Font("Arial", 8), Brushes.Black, 0, 0)
                                                e.HasMorePages = False
                                            End Sub

        printDocument.Print()
    End Sub

    Public Shared Sub SendZplOverUsb(ByVal barcode As String, ByVal usbDriverName As String)
        Dim thePrinterConn As Connection = Nothing
        Dim width As Integer = 3
        Dim height As Integer = 150
        Dim marginLeft As Integer = 150
        Dim marginTop As Integer = 10
        Try
            '  thePrinterConn = ConnectionBuilder.Build($"USB:{usbDriverName}")
            thePrinterConn = ConnectionBuilder.Build($"USB_DIRECT:{usbDriverName}")
            thePrinterConn.Open()

            Dim mystr = String.Format("^XA^FO{0},{1}^BY{2}^BCN,{3},N,N^FD{4}^FS^FO{5},{6}^A0N,30,20^FD{4}^FS^XZ", marginLeft, marginTop, width, height, barcode, marginLeft, marginTop + height + 10)

            thePrinterConn.Write(Encoding.UTF8.GetBytes(barcode))
        Catch e As ConnectionException
            Debug.WriteLine(e.ToString())
        Finally

            If thePrinterConn IsNot Nothing Then
                thePrinterConn.Close()
            End If
        End Try
    End Sub

    Public Shared Function UsbDriverlessTest() As ArrayList
        Dim myusb As New ArrayList()
        Dim printers As List(Of DiscoveredPrinterDriver) = UsbDiscoverer.GetZebraDriverPrinters()

        Console.WriteLine("Zebra printers installed using Zebra driversx:")
        For Each printer As DiscoveredPrinterDriver In printers
            Debug.WriteLine(printer.ToString())
            'myusb.Add(printer.ToString)
        Next


        Return myusb

    End Function

    'Module Module1
    '    Sub Main()
    '        ' Set up the serial port
    '        Dim port As New SerialPort("COM3", 9600, Parity.None, 8, StopBits.One) ' Adjust COM port and settings as needed

    '        Try
    '            port.Open()
    '            ' ZPL command to print "Hello, World!"
    '            Dim zplCommand As String = "^XA" & vbCrLf &
    '                                        "^FO50,50^ADN,36,20^FDHello, World!^FS" & vbCrLf &
    '                                        "^XZ"
    '            port.Write(zplCommand)
    '            Console.WriteLine("ZPL command sent to printer.")
    '        Catch ex As Exception
    '            Console.WriteLine("Error: " & ex.Message)
    '        Finally
    '            If port.IsOpen Then
    '                port.Close()
    '            End If
    '        End Try
    '    End Sub
    'End Module

End Class
