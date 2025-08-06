Imports CrystalDecisions.CrystalReports.Engine
Imports CrystalDecisions.Shared
Imports Newtonsoft.Json
Imports Newtonsoft.Json.Linq
Imports System.IO

Public Class RptPreweighfrm
    Public itemkey As String = ""
    Public lotno As String = ""
    Public qty As String = ""
    Public batchno As String = ""
    Public allergen As String = ""
    Private Sub RptPreweighfrm_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        'Dim tempQrImagePath As String = GenerateQRCodeToTempFile("aris")
        Dim qrImage As Bitmap = GenerateQRCode(itemkey & "*" & qty)
        Dim imageData As Byte() = ConvertImageToByteArray(qrImage)

        Dim ds As New DataSet()
        Dim dt As New DataTable("preweigh_wt")


        dt.Columns.Add("itemkey", GetType(String))
        dt.Columns.Add("lotno", GetType(String))
        dt.Columns.Add("qty", GetType(String))
        dt.Columns.Add("batchno", GetType(String))
        dt.Columns.Add("allergen", GetType(String))
        dt.Columns.Add("qr", GetType(Byte()))

        Dim row As DataRow = dt.NewRow()
        row("itemkey") = itemkey
        row("lotno") = lotno
        row("qty") = qty
        row("batchno") = batchno
        row("allergen") = allergen
        row("qr") = imageData
        dt.Rows.Add(row)


        ds.Tables.Add(dt)

        Dim report As New ReportDocument()
        report.Load("Report/preweigh_wt.rpt")
        report.SetDataSource(ds)

        CrystalReportViewer1.ReportSource = report
        CrystalReportViewer1.Refresh()
    End Sub
End Class