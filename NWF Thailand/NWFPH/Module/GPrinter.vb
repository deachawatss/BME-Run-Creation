Imports System.Drawing.Printing
Imports System.IO
Imports System.Runtime.InteropServices
Module GPrinter

    <DllImport("winspool.drv", CharSet:=CharSet.Auto, SetLastError:=True)>
    Public Function OpenPrinter(ByVal szPrinter As String, ByRef hPrinter As IntPtr, ByVal pDefault As IntPtr) As Boolean
    End Function

    <DllImport("winspool.drv", CharSet:=CharSet.Auto, SetLastError:=True)>
    Public Function ClosePrinter(ByVal hPrinter As IntPtr) As Boolean
    End Function

    <DllImport("winspool.drv", CharSet:=CharSet.Auto, SetLastError:=True)>
    Public Function StartDocPrinter(ByVal hPrinter As IntPtr, ByVal level As Integer, ByRef pDocInfo As DOCINFO) As Boolean
    End Function

    <DllImport("winspool.drv", CharSet:=CharSet.Auto, SetLastError:=True)>
    Public Function EndDocPrinter(ByVal hPrinter As IntPtr) As Boolean
    End Function

    <DllImport("winspool.drv", CharSet:=CharSet.Auto, SetLastError:=True)>
    Public Function WritePrinter(ByVal hPrinter As IntPtr, ByVal pBytes As IntPtr, ByVal dwCount As Integer, ByRef dwWritten As Integer) As Boolean
    End Function

    <StructLayout(LayoutKind.Sequential)>
    Public Structure DOCINFO
        <MarshalAs(UnmanagedType.LPTStr)>
        Public pDocName As String
        <MarshalAs(UnmanagedType.LPTStr)>
        Public pOutputFile As String
        <MarshalAs(UnmanagedType.LPTStr)>
        Public pDatatype As String
    End Structure

    Sub Mainx()
        ' Get all installed printers
        Dim printers As PrinterSettings.StringCollection = PrinterSettings.InstalledPrinters

        Console.WriteLine("Available USB Printers:")

        ' Loop through all installed printers
        For Each printerName As String In printers
            Dim printerInfo As New PrinterSettings()
            printerInfo.PrinterName = printerName
            Console.WriteLine(printerInfo.PrinterName)
            ' Check if the printer is USB
            If printerInfo.IsDefaultPrinter OrElse IsUsbPrinter(printerInfo) Then
                ' Console.WriteLine($"- {printerName}")
            End If
        Next

        Console.ReadLine()
    End Sub

    ' Function to check if the printer is USB
    Function IsUsbPrinter(printerSettings As PrinterSettings) As Boolean
        Try
            ' Get the port name of the printer
            Dim portName As String = printerSettings.PrinterName
            ' Check if the port name contains "USB"
            Return portName.ToUpper().Contains("USB")
        Catch ex As Exception
            ' Handle exceptions if necessary
            Return False
        End Try
    End Function

    Public Sub RawPrint(ByVal printerName As String, ByVal zplCommand As String)
        Dim hPrinter As IntPtr
        Dim di As New DOCINFO()
        di.pDocName = "ZPL Document"
        di.pOutputFile = Nothing
        di.pDatatype = "RAW"

        If OpenPrinter(printerName, hPrinter, IntPtr.Zero) Then
            StartDocPrinter(hPrinter, 1, di)

            Dim bytes As Byte() = System.Text.Encoding.ASCII.GetBytes(zplCommand)
            Dim dwWritten As Integer

            WritePrinter(hPrinter, Marshal.UnsafeAddrOfPinnedArrayElement(bytes, 0), bytes.Length, dwWritten)

            EndDocPrinter(hPrinter)
            ClosePrinter(hPrinter)
        Else
            Console.WriteLine("Could not open printer.")
        End If
    End Sub

End Module
