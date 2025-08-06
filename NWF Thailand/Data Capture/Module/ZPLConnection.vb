Imports System
Imports System.IO
Imports System.Text
Imports Newtonsoft.Json.Linq
Imports Org.BouncyCastle.Utilities
Imports Zebra.Sdk.Comm
Imports Zebra.Sdk.Printer
Imports Zebra.Sdk.Printer.Discovery

Public Class ZPLConnection
    Public Shared Sub Main(ByVal args As String())
        ' UsbDriverlessTest()
        'New ZPLConnection().NonBlockingStatusReportingOverMultichannel("1.2.3.4")
        'ZPLConnection().SendZplOverTcp("1.2.3.4")
        'ZPLConnection().SendZplOverUsb("ZDesigner GK420t")
        'ZPLConnection().SendZplOverBluetooth("11:22:33:44:55:66")
    End Sub

    Public Function UsbDriverlessTest() As ArrayList
        Debug.WriteLine("Discovered USB printer list:" & vbCrLf)

        Dim myusb As New ArrayList()

        For Each printer As DiscoveredUsbPrinter In UsbDiscoverer.GetZebraUsbPrinters(New ZebraPrinterFilter())
            Debug.WriteLine(printer)
            myusb.Add(printer.ToString)
            'myusb.
        Next

        Dim printers As List(Of DiscoveredPrinterDriver) = UsbDiscoverer.GetZebraDriverPrinters()
        ' Console.WriteLine("Zebra printers installed using Zebra drivers:")
        For Each printer As DiscoveredPrinterDriver In printers
            Debug.WriteLine(printer.ToString())
        Next

        'Debug.WriteLine("End USB printer list" & vbCrLf)
        Dim imz As Connection = Nothing

        'Try
        'imz = ConnectionBuilder.Build("\\?\usb#vid_0a5f&amp;pid_00f2#imz220#...")
        'imz.Open()
        'Dim hi_return As Byte() = imz.SendAndWaitForResponse(Encoding.UTF8.GetBytes("~HI"), 5000, 10000, "V")
        'Debug.WriteLine(Encoding.UTF8.GetString(hi_return))
        'Finally

        'If imz IsNot Nothing Then
        'imz.Close()
        'End If
        'End Try

        Return myusb
    End Function

    Public Sub NonBlockingStatusReportingOverMultichannel(ByVal theIpAddress As String)
        Dim thePrinterConn As Connection = Nothing

        Try
            thePrinterConn = ConnectionBuilder.Build($"TCP_MULTI:{theIpAddress}:9100:9200")
            thePrinterConn.Open()
            Dim linkOsPrinter As ZebraPrinterLinkOs = ZebraPrinterFactory.GetLinkOsPrinter(thePrinterConn)
            Dim labelFormatStartCommand As String = "^XA"
            linkOsPrinter.SendCommand(labelFormatStartCommand)
            Dim labelBody As String = "^FO20,20^A0N,25,25^FDThis is a ZPL test.^FS"
            linkOsPrinter.SendCommand(labelBody)
            Dim status As PrinterStatus = linkOsPrinter.GetCurrentStatus()
            Debug.WriteLine($"The printer PAUSED state is: {status.isPaused}")
            Dim labelFormatEndCommand As String = "^XZ"
            linkOsPrinter.SendCommand(labelFormatEndCommand)
        Catch e As ConnectionException
            Debug.WriteLine(e.ToString())
        Finally

            If thePrinterConn IsNot Nothing Then
                thePrinterConn.Close()
            End If
        End Try
    End Sub

    Public Sub SendZplOverTcp(ByVal theIpAddress As String)
        Dim thePrinterConn As Connection = Nothing

        Try
            thePrinterConn = ConnectionBuilder.Build($"TCP:{theIpAddress}:9100")
            thePrinterConn.Open()
            Dim zplData As String = "^XA^FO20,20^A0N,25,25^FDThis is a ZPL test.^FS^XZ"
            thePrinterConn.Write(Encoding.UTF8.GetBytes(zplData))
        Catch e As ConnectionException
            Debug.WriteLine(e.ToString())
        Finally

            If thePrinterConn IsNot Nothing Then
                thePrinterConn.Close()
            End If
        End Try
    End Sub
    Public Sub SendZplOverUsb(ByVal usbDriverName As String, ByVal itemkey As String, ByVal qty As String, ByVal lotno As String, ByVal barcode As String, ByVal batchno As String)
        Dim thePrinterConn As Connection = Nothing

        Try
            thePrinterConn = ConnectionBuilder.Build($"USB_DIRECT:{usbDriverName}")
            'thePrinterConn = ConnectionBuilder.Build("ZDesigner ZD421-203dpi ZPL")
            thePrinterConn.Open()
            'Dim zplData As String = "^XA^FO20,20^A0N,25,25^FDThis is a ZPL test.^FS^XZ"
            Dim genericPrinter As ZebraPrinter = ZebraPrinterFactory.GetInstance(thePrinterConn)
            Dim linkOsPrinter As ZebraPrinterLinkOs = ZebraPrinterFactory.CreateLinkOsPrinter(genericPrinter)

            If linkOsPrinter IsNot Nothing Then
                Dim MyDict As New Dictionary(Of Integer, String)
                MyDict.Add(1, itemkey) 'itemkey
                MyDict.Add(2, qty) 'qty
                MyDict.Add(3, lotno)    'lotno
                MyDict.Add(4, barcode)   'barcode
                MyDict.Add(5, batchno) 'batchno

                linkOsPrinter.PrintStoredFormat("E:partialprint.ZPL", MyDict)
            End If


            ' thePrinterConn.Write(Encoding.UTF8.GetBytes(zplData))
        Catch e As ConnectionException
            Debug.WriteLine(e.ToString())
        Finally

            If thePrinterConn IsNot Nothing Then
                thePrinterConn.Close()
            End If
        End Try
    End Sub

    Public Sub SendZplOverUsb2(ByVal usbDriverName As String)
        Dim thePrinterConn As Connection = Nothing

        Try
            thePrinterConn = ConnectionBuilder.Build($"USB_DIRECT:{usbDriverName}")
            'thePrinterConn = ConnectionBuilder.Build("ZDesigner ZD421-203dpi ZPL")
            thePrinterConn.Open()
            Dim zplData As String = String.Format("^XA
                ^FT24,39^A0N,39,38^FH\^CI28^FDItem key:{0}^FS^CI27
                ^FT24,90^A0N,39,38^FH\^CI28^FDLot No:{1}^FS^CI27
                ^FT24,138^A0N,39,38^FH\^CI28^FDQuantity:{2}^FS^CI27
                ^FT468,40^A0N,39,38^FH\^CI28^FDBatch No:{3}^FS^CI27
                ^FO389,150^BY3
                ^BCN,100,Y,N,N
                ^FDPARTIAL-0000001^FS
             ^XZ")


            Dim mystr = "
                ^XA
                ^DFE:partialprint.ZPL^FS
                ~TA000
                ~JSN
                ^LT0
                ^MNW
                ^MTT
                ^PON
                ^PMN
                ^LH0,0
                ^JMA
                ^PR4,4
                ~SD15
                ^JUS
                ^LRN
                ^CI27
                ^PA0,1,1,0
                ^MMT
                ^PW812
                ^LL203
                ^LS0
                
                ^XZ
                "


            thePrinterConn.Write(Encoding.UTF8.GetBytes(zplData))
        Catch e As ConnectionException
            Debug.WriteLine(e.ToString())
        Finally

            If thePrinterConn IsNot Nothing Then
                thePrinterConn.Close()
            End If
        End Try
    End Sub

    Public Sub SendZplOverBluetooth(ByVal btMacAddress As String)
        Dim thePrinterConn As Connection = Nothing

        Try
            thePrinterConn = ConnectionBuilder.Build($"BT:{btMacAddress}")
            thePrinterConn.Open()
            Dim zplData As String = "^XA^FO20,20^A0N,25,25^FDThis is a ZPL test.^FS^XZ"
            thePrinterConn.Write(Encoding.UTF8.GetBytes(zplData))
        Catch e As ConnectionException
            Debug.WriteLine(e.ToString())
        Finally

            If thePrinterConn IsNot Nothing Then
                thePrinterConn.Close()
            End If
        End Try
    End Sub

    Private Function GetSampleXmlData() As Stream
        Dim sampleXmlData As String = "<?xml version=""1.0"" encoding=""UTF-8""?>" &
            "<file _FORMAT=""XmlExamp.zpl"">" &
            " <label>\n" &
            " <variable name=""Name"">John Smith</variable>" &
            " <variable name=""Street"">1234 Anystreet</variable>" &
            " <variable name=""City"">Anycity</variable>" &
            " <variable name=""State"">Anystate</variable>" &
            " <variable name=""Zip"">12345</variable>" &
            " </label>\n" &
            "</file>"
        Return New MemoryStream(Encoding.UTF8.GetBytes(sampleXmlData))
    End Function

    Public Async Function UsbTest() As Task(Of List(Of String))
        Dim printers As List(Of DiscoveredPrinterDriver) = UsbDiscoverer.GetZebraDriverPrinters()
        Dim myusb As New List(Of String)()

        For Each printer As DiscoveredPrinterDriver In printers
            Debug.WriteLine(printer.ToString())
            myusb.Add(printer.ToString())
        Next

        Return myusb
    End Function


End Class
