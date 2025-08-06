<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()> _
Partial Class frmFetchWtItemList
    Inherits System.Windows.Forms.Form

    'Form overrides dispose to clean up the component list.
    <System.Diagnostics.DebuggerNonUserCode()> _
    Protected Overrides Sub Dispose(ByVal disposing As Boolean)
        Try
            If disposing AndAlso components IsNot Nothing Then
                components.Dispose()
            End If
        Finally
            MyBase.Dispose(disposing)
        End Try
    End Sub

    'Required by the Windows Form Designer
    Private components As System.ComponentModel.IContainer

    'NOTE: The following procedure is required by the Windows Form Designer
    'It can be modified using the Windows Form Designer.  
    'Do not modify it using the code editor.
    <System.Diagnostics.DebuggerStepThrough()> _
    Private Sub InitializeComponent()
        Me.DataGridView1 = New System.Windows.Forms.DataGridView()
        Me.Itemkey = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.Desc1 = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.LotNo = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.Dateexpiry = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.qty = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.Committed = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.QtyAvailable = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.myrequired = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.wtfrom = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.wtto = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.statflag = New System.Windows.Forms.DataGridViewTextBoxColumn()
        CType(Me.DataGridView1, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'DataGridView1
        '
        Me.DataGridView1.AllowUserToAddRows = False
        Me.DataGridView1.AllowUserToDeleteRows = False
        Me.DataGridView1.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize
        Me.DataGridView1.Columns.AddRange(New System.Windows.Forms.DataGridViewColumn() {Me.Itemkey, Me.Desc1, Me.LotNo, Me.Dateexpiry, Me.qty, Me.Committed, Me.QtyAvailable, Me.myrequired, Me.wtfrom, Me.wtto, Me.statflag})
        Me.DataGridView1.Dock = System.Windows.Forms.DockStyle.Fill
        Me.DataGridView1.Location = New System.Drawing.Point(0, 0)
        Me.DataGridView1.Name = "DataGridView1"
        Me.DataGridView1.ReadOnly = True
        Me.DataGridView1.Size = New System.Drawing.Size(600, 366)
        Me.DataGridView1.TabIndex = 3
        '
        'Itemkey
        '
        Me.Itemkey.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.Itemkey.HeaderText = "Itemkey"
        Me.Itemkey.Name = "Itemkey"
        Me.Itemkey.ReadOnly = True
        '
        'Desc1
        '
        Me.Desc1.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.Desc1.HeaderText = "Description"
        Me.Desc1.Name = "Desc1"
        Me.Desc1.ReadOnly = True
        '
        'LotNo
        '
        Me.LotNo.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.LotNo.HeaderText = "Lot No"
        Me.LotNo.Name = "LotNo"
        Me.LotNo.ReadOnly = True
        '
        'Dateexpiry
        '
        Me.Dateexpiry.HeaderText = "Expiry"
        Me.Dateexpiry.Name = "Dateexpiry"
        Me.Dateexpiry.ReadOnly = True
        '
        'qty
        '
        Me.qty.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.qty.HeaderText = "Quantity"
        Me.qty.Name = "qty"
        Me.qty.ReadOnly = True
        '
        'Committed
        '
        Me.Committed.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.Committed.HeaderText = "Committed"
        Me.Committed.Name = "Committed"
        Me.Committed.ReadOnly = True
        '
        'QtyAvailable
        '
        Me.QtyAvailable.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.QtyAvailable.HeaderText = "Quantity Available"
        Me.QtyAvailable.Name = "QtyAvailable"
        Me.QtyAvailable.ReadOnly = True
        '
        'myrequired
        '
        Me.myrequired.HeaderText = "myrequired"
        Me.myrequired.Name = "myrequired"
        Me.myrequired.ReadOnly = True
        Me.myrequired.Visible = False
        '
        'wtfrom
        '
        Me.wtfrom.HeaderText = "wtfrom"
        Me.wtfrom.Name = "wtfrom"
        Me.wtfrom.ReadOnly = True
        Me.wtfrom.Visible = False
        '
        'wtto
        '
        Me.wtto.HeaderText = "wtto"
        Me.wtto.Name = "wtto"
        Me.wtto.ReadOnly = True
        Me.wtto.Visible = False
        '
        'statflag
        '
        Me.statflag.HeaderText = "Status"
        Me.statflag.Name = "statflag"
        Me.statflag.ReadOnly = True
        '
        'frmFetchWtItemList
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(6.0!, 13.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
        Me.ClientSize = New System.Drawing.Size(600, 366)
        Me.Controls.Add(Me.DataGridView1)
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedToolWindow
        Me.Margin = New System.Windows.Forms.Padding(2)
        Me.Name = "frmFetchWtItemList"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "Item List"
        CType(Me.DataGridView1, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

    Friend WithEvents DataGridView1 As DataGridView
    Friend WithEvents Itemkey As DataGridViewTextBoxColumn
    Friend WithEvents Desc1 As DataGridViewTextBoxColumn
    Friend WithEvents LotNo As DataGridViewTextBoxColumn
    Friend WithEvents Dateexpiry As DataGridViewTextBoxColumn
    Friend WithEvents qty As DataGridViewTextBoxColumn
    Friend WithEvents Committed As DataGridViewTextBoxColumn
    Friend WithEvents QtyAvailable As DataGridViewTextBoxColumn
    Friend WithEvents myrequired As DataGridViewTextBoxColumn
    Friend WithEvents wtfrom As DataGridViewTextBoxColumn
    Friend WithEvents wtto As DataGridViewTextBoxColumn
    Friend WithEvents statflag As DataGridViewTextBoxColumn
End Class
