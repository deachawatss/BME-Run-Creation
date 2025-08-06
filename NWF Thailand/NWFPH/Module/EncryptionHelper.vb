Imports System.Security.Cryptography
Imports System.Text

Public Class EncryptionHelper
    Private Const SaltSize As Integer = 16 ' in bytes
    Private Const Iterations As Integer = 10000
    Private Const pword As String = "Newlyweds"

    Public Function EncryptString(ByVal plainText As String) As String
        Dim password As String = pword
        Dim salt(SaltSize - 1) As Byte
        Dim rngCryptoServiceProvider As New RNGCryptoServiceProvider()
        rngCryptoServiceProvider.GetBytes(salt)

        Dim passwordDerivedBytes As New Rfc2898DeriveBytes(password, salt, Iterations)
        Dim key() As Byte = passwordDerivedBytes.GetBytes(32)
        Dim iv() As Byte = passwordDerivedBytes.GetBytes(16)

        Using aes As New AesCryptoServiceProvider()
            aes.Key = key
            aes.IV = iv

            Dim encryptor As ICryptoTransform = aes.CreateEncryptor()
            Dim plainTextBytes() As Byte = Encoding.UTF8.GetBytes(plainText)
            Dim cipherTextBytes() As Byte = encryptor.TransformFinalBlock(plainTextBytes, 0, plainTextBytes.Length)
            Dim cipherTextWithSaltBytes(cipherTextBytes.Length + SaltSize - 1) As Byte
            Buffer.BlockCopy(salt, 0, cipherTextWithSaltBytes, 0, SaltSize)
            Buffer.BlockCopy(cipherTextBytes, 0, cipherTextWithSaltBytes, SaltSize, cipherTextBytes.Length)

            Return Convert.ToBase64String(cipherTextWithSaltBytes)
        End Using
    End Function

    Public Function DecryptString(ByVal cipherText As String) As String
        Dim cipherTextWithSaltBytes() As Byte = Convert.FromBase64String(cipherText)
        Dim password As String = pword

        Dim salt(SaltSize - 1) As Byte
        Buffer.BlockCopy(cipherTextWithSaltBytes, 0, salt, 0, SaltSize)

        Dim passwordDerivedBytes As New Rfc2898DeriveBytes(password, salt, Iterations)
        Dim key() As Byte = passwordDerivedBytes.GetBytes(32)
        Dim iv() As Byte = passwordDerivedBytes.GetBytes(16)

        Using aes As New AesCryptoServiceProvider()
            aes.Key = key
            aes.IV = iv

            Dim decryptor As ICryptoTransform = aes.CreateDecryptor()
            Dim plainTextBytes() As Byte = decryptor.TransformFinalBlock(cipherTextWithSaltBytes, SaltSize, cipherTextWithSaltBytes.Length - SaltSize)
            Return Encoding.UTF8.GetString(plainTextBytes)
        End Using
    End Function
End Class
