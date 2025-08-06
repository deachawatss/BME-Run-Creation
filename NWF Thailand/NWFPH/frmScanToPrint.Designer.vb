<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()> _
Partial Class frmScanToPrint
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
        Me.components = New System.ComponentModel.Container()
        Me.Label1 = New System.Windows.Forms.Label()
        Me.txtbarcode = New System.Windows.Forms.TextBox()
        Me.txtqty = New System.Windows.Forms.TextBox()
        Me.Label2 = New System.Windows.Forms.Label()
        Me.Button1 = New System.Windows.Forms.Button()
        Me.Timer1 = New System.Windows.Forms.Timer(Me.components)
        Me.txtscale = New System.Windows.Forms.ComboBox()
        Me.Label3 = New System.Windows.Forms.Label()
        Me.txtbatchno = New System.Windows.Forms.TextBox()
        Me.Label4 = New System.Windows.Forms.Label()
        Me.SuspendLayout()
        '
        'Label1
        '
        Me.Label1.AutoSize = True
        Me.Label1.Font = New System.Drawing.Font("Microsoft Sans Serif", 20.25!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label1.ForeColor = System.Drawing.Color.FromArgb(CType(CType(249, Byte), Integer), CType(CType(250, Byte), Integer), CType(CType(244, Byte), Integer))
        Me.Label1.Location = New System.Drawing.Point(25, 134)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(122, 31)
        Me.Label1.TabIndex = 0
        Me.Label1.Text = "Barcode"
        '
        'txtbarcode
        '
        Me.txtbarcode.Anchor = CType(((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.txtbarcode.Font = New System.Drawing.Font("Microsoft Sans Serif", 20.25!, System.Drawing.FontStyle.Bold)
        Me.txtbarcode.Location = New System.Drawing.Point(158, 131)
        Me.txtbarcode.Name = "txtbarcode"
        Me.txtbarcode.Size = New System.Drawing.Size(621, 38)
        Me.txtbarcode.TabIndex = 1
        '
        'txtqty
        '
        Me.txtqty.Anchor = CType(((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.txtqty.Font = New System.Drawing.Font("Microsoft Sans Serif", 20.25!, System.Drawing.FontStyle.Bold)
        Me.txtqty.Location = New System.Drawing.Point(158, 175)
        Me.txtqty.Name = "txtqty"
        Me.txtqty.Size = New System.Drawing.Size(187, 38)
        Me.txtqty.TabIndex = 3
        Me.txtqty.Text = "0"
        Me.txtqty.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'Label2
        '
        Me.Label2.AutoSize = True
        Me.Label2.Font = New System.Drawing.Font("Microsoft Sans Serif", 20.25!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label2.ForeColor = System.Drawing.Color.FromArgb(CType(CType(249, Byte), Integer), CType(CType(250, Byte), Integer), CType(CType(244, Byte), Integer))
        Me.Label2.Location = New System.Drawing.Point(25, 178)
        Me.Label2.Name = "Label2"
        Me.Label2.Size = New System.Drawing.Size(124, 31)
        Me.Label2.TabIndex = 2
        Me.Label2.Text = "Quantity"
        '
        'Button1
        '
        Me.Button1.Anchor = CType(((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.Button1.Font = New System.Drawing.Font("Microsoft Sans Serif", 20.25!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Button1.Image = Global.NWFTH.My.Resources.Resources.icons8_print_100
        Me.Button1.ImageAlign = System.Drawing.ContentAlignment.MiddleLeft
        Me.Button1.Location = New System.Drawing.Point(279, 218)
        Me.Button1.MaximumSize = New System.Drawing.Size(230, 96)
        Me.Button1.MinimumSize = New System.Drawing.Size(230, 96)
        Me.Button1.Name = "Button1"
        Me.Button1.Size = New System.Drawing.Size(230, 96)
        Me.Button1.TabIndex = 4
        Me.Button1.Text = "             PRINT"
        Me.Button1.UseVisualStyleBackColor = True
        '
        'Timer1
        '
        '
        'txtscale
        '
        Me.txtscale.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.txtscale.Font = New System.Drawing.Font("Microsoft Sans Serif", 20.25!, System.Drawing.FontStyle.Bold)
        Me.txtscale.FormattingEnabled = True
        Me.txtscale.Items.AddRange(New Object() {"SCALE 1", "SCALE 2"})
        Me.txtscale.Location = New System.Drawing.Point(158, 43)
        Me.txtscale.Name = "txtscale"
        Me.txtscale.Size = New System.Drawing.Size(278, 39)
        Me.txtscale.TabIndex = 5
        '
        'Label3
        '
        Me.Label3.AutoSize = True
        Me.Label3.Font = New System.Drawing.Font("Microsoft Sans Serif", 20.25!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label3.ForeColor = System.Drawing.Color.FromArgb(CType(CType(249, Byte), Integer), CType(CType(250, Byte), Integer), CType(CType(244, Byte), Integer))
        Me.Label3.Location = New System.Drawing.Point(25, 47)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(130, 31)
        Me.Label3.TabIndex = 6
        Me.Label3.Text = "Wt Scale"
        '
        'txtbatchno
        '
        Me.txtbatchno.Anchor = CType(((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.txtbatchno.Font = New System.Drawing.Font("Microsoft Sans Serif", 20.25!, System.Drawing.FontStyle.Bold)
        Me.txtbatchno.Location = New System.Drawing.Point(158, 87)
        Me.txtbatchno.Name = "txtbatchno"
        Me.txtbatchno.Size = New System.Drawing.Size(370, 38)
        Me.txtbatchno.TabIndex = 8
        Me.txtbatchno.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'Label4
        '
        Me.Label4.AutoSize = True
        Me.Label4.Font = New System.Drawing.Font("Microsoft Sans Serif", 20.25!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label4.ForeColor = System.Drawing.Color.FromArgb(CType(CType(249, Byte), Integer), CType(CType(250, Byte), Integer), CType(CType(244, Byte), Integer))
        Me.Label4.Location = New System.Drawing.Point(25, 90)
        Me.Label4.Name = "Label4"
        Me.Label4.Size = New System.Drawing.Size(134, 31)
        Me.Label4.TabIndex = 7
        Me.Label4.Text = "Batch No"
        '
        'frmScanToPrint
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(96.0!, 96.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Dpi
        Me.BackColor = System.Drawing.Color.FromArgb(CType(CType(112, Byte), Integer), CType(CType(119, Byte), Integer), CType(CType(147, Byte), Integer))
        Me.ClientSize = New System.Drawing.Size(843, 324)
        Me.Controls.Add(Me.txtbatchno)
        Me.Controls.Add(Me.Label4)
        Me.Controls.Add(Me.Label3)
        Me.Controls.Add(Me.txtscale)
        Me.Controls.Add(Me.Button1)
        Me.Controls.Add(Me.txtqty)
        Me.Controls.Add(Me.Label2)
        Me.Controls.Add(Me.txtbarcode)
        Me.Controls.Add(Me.Label1)
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.None
        Me.Name = "frmScanToPrint"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterParent
        Me.Text = "frmScanToPrint"
        Me.ResumeLayout(False)
        Me.PerformLayout()

    End Sub

    Friend WithEvents Label1 As Label
    Friend WithEvents txtbarcode As TextBox
    Friend WithEvents txtqty As TextBox
    Friend WithEvents Label2 As Label
    Friend WithEvents Button1 As Button
    Friend WithEvents Timer1 As Timer
    Friend WithEvents txtscale As ComboBox
    Friend WithEvents Label3 As Label
    Friend WithEvents txtbatchno As TextBox
    Friend WithEvents Label4 As Label
End Class
