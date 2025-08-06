Public Class frmprintest
    Private Sub frmprintest_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        Mainx()
        Dim zplCommand As String = "^XA" & vbCrLf &
                                   "^FO50,50^ADN,36,20^FDHello World^FS" & vbCrLf &
                                   "^XZ"

        RawPrint("ZDesigner ZD421-300dpi ZPL", zplCommand) ' Adjust if necessary
        Console.WriteLine("ZPL command sent to USB printer successfully.")
    End Sub
End Class