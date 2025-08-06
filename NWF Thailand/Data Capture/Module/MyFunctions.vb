Imports System.IO
Imports Newtonsoft.Json.Linq

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

    Public Function chkifvalid() As Boolean

        Return False
    End Function

    Sub LogError(ByVal ex As Exception)

        'Dim debugstatus = myreg("debugstatus")
        'Dim logFilePath As String = "error.log"

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

    Sub DebugLog(ByVal ex As String)

        'Dim debugstatus = myreg("debugstatus")
        'Dim logFilePath As String = "error.log"

        'Using writer As StreamWriter = File.AppendText(logFilePath)
        '    writer.WriteLine("Error Date/Time: " & DateTime.Now.ToString())
        '    writer.WriteLine("Error Message: " & ex)
        '    'writer.WriteLine("Stack Trace: " & ex.StackTrace)
        '    writer.WriteLine("--------------------------------------------------")
        'End Using

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


    Function JObjectToDictionary(jsonObject As JObject) As Dictionary(Of String, Object)
        Return jsonObject.ToObject(Of Dictionary(Of String, Object))()
    End Function
End Module
