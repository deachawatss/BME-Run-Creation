Module UserInfo
    Public userinfo As Dictionary(Of String, Object) = Nothing
    Public isLogin As Integer = 0


    Public Function getUserinfo(Optional ByVal field As String = "")

        If field IsNot "" Then
            Return userinfo.Item(field).ToString
        Else
            Return userinfo
        End If

    End Function

    Public Sub setUserinfo(key As String, val As String)
        userinfo.Add(key, val)
    End Sub

    Public Sub clearUserinfo()
        userinfo.Clear()
    End Sub

End Module
