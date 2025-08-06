Imports CrystalDecisions.CrystalReports.Engine
Imports Newtonsoft.Json
Imports Newtonsoft.Json.Linq
Imports System.IO
Public Class RptRunFrm
    Dim api As New ApiClass()
    Public myrunno As String = ""
    Private Sub RptRunFrm_Load(sender As Object, e As EventArgs) Handles MyBase.Load

        Try
            '{"no", "P25000002"}
            Dim postData As New Dictionary(Of String, String) From {
                    {"no", myrunno}
                }
            Dim mydata = api.post_request("PartialRpt/rpt", postData)
            'Dim jsonObject As JObject = DirectCast(mydata, JObject)

            Dim jrunlist As JArray = DirectCast(mydata.Item("runlist"), JArray)
            Dim jrundata As JArray = DirectCast(mydata.Item("rundata"), JArray)


            Dim runlist As DataTable = JArrayToDataTable(jrunlist, "runlist")
            'runlist.TableName = "runlist"
            Dim rundata As DataTable = JArrayToDataTable(jrundata, "rundata")

            If jrunlist.Count > 0 Then
                Dim ds As New DataSet()
                ds.Tables.Add(runlist)
                ds.Tables.Add(rundata)

                Dim report As New ReportDocument()
                report.Load("report/runhdrrpt.rpt")
                report.SetDataSource(ds)
                CrystalReportViewer1.ReportSource = report
            Else
                MsgBox("No Record(s) Found")
                Me.Close()
            End If


        Catch ex As Exception
            Debug.WriteLine(ex)
        End Try


    End Sub
End Class