Imports Microsoft.Win32

Public Class RegistryCRUD
    Private Const myReg As String = "Software\\NWF MOBILE"
    Public Sub CreateRegistryValue(ByVal valueName As String, ByVal valueData As Object, ByVal valueKind As RegistryValueKind)
        ' Create a new registry key
        Dim key As RegistryKey = Registry.CurrentUser.CreateSubKey(myReg)

        ' Set the value of the specified registry value name
        key.SetValue(valueName, valueData, valueKind)

        ' Close the registry key
        key.Close()
    End Sub

    Public Function ReadRegistryValue(ByVal valueName As String, ByVal defaultValue As Object) As Object
        ' Open the registry key
        Dim key As RegistryKey = Registry.CurrentUser.OpenSubKey(myReg)

        ' If the registry key doesn't exist, return the default value
        If key Is Nothing Then
            Return defaultValue
        End If

        ' Read the value of the specified registry value name
        Dim value As Object = key.GetValue(valueName, defaultValue)

        ' Close the registry key
        key.Close()

        Return value
    End Function

    Public Sub UpdateRegistryValue(ByVal valueName As String, ByVal valueData As Object, ByVal valueKind As RegistryValueKind)
        ' Open the registry key
        Dim key As RegistryKey = Registry.CurrentUser.OpenSubKey(myReg, True)

        ' If the registry key doesn't exist, create it
        If key Is Nothing Then
            key = Registry.CurrentUser.CreateSubKey(myReg)
        End If

        ' Set the value of the specified registry value name
        key.SetValue(valueName, valueData, valueKind)

        ' Close the registry key
        key.Close()
    End Sub

    Public Sub DeleteRegistryValue(ByVal valueName As String)
        ' Open the registry key
        Dim key As RegistryKey = Registry.CurrentUser.OpenSubKey(myReg, True)

        ' If the registry key doesn't exist, do nothing
        If key Is Nothing Then
            Return
        End If

        ' Delete the specified registry value name
        key.DeleteValue(valueName, False)

        ' Close the registry key
        key.Close()
    End Sub

End Class
