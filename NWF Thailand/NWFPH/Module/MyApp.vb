Imports System.IO.Ports

Module MyApp
    'Public myscale As SerialScale
    Public myport As SerialPort

    Public Function NotNull(Of T)(ByVal Value As T, ByVal DefaultValue As T) As T
        Try
            If Value Is Nothing OrElse IsDBNull(Value) Then
                Return DefaultValue
            Else
                Return Value
            End If
        Catch ex As Exception
            Return DefaultValue
        End Try

    End Function

End Module
